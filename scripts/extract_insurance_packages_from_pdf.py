#!/usr/bin/env python3
"""
Extract insurance surgical package rows from scanned PDFs via OCR.
Outputs CSV files for: php artisan insurance:import-packages

Usage:
  python scripts/extract_insurance_packages_from_pdf.py
  python scripts/extract_insurance_packages_from_pdf.py --pdf "path/to/file.pdf" --panel GIPSA_PPN
"""

from __future__ import annotations

import argparse
import csv
import os
import re
import sys
from pathlib import Path

try:
    import fitz  # PyMuPDF
except ImportError:
    print("Install PyMuPDF: pip install pymupdf", file=sys.stderr)
    sys.exit(1)

try:
    import easyocr
except ImportError:
    print("Install EasyOCR: pip install easyocr", file=sys.stderr)
    sys.exit(1)

ROOT = Path(__file__).resolve().parents[1]
OUT_DIR = ROOT / "database" / "imports" / "insurance_packages"

DEFAULT_PDFS = [
    {
        "path": r"c:\Users\INTEL\Downloads\SAMARITAN CLINIC SIGNED PPN PACKAGE.pdf",
        "panel_code": "GIPSA_PPN",
        "panel_name": "GIPSA PPN Samaritan 2022",
        "parser": "gipsa",
    },
    {
        "path": r"c:\Users\INTEL\Downloads\UPDATE STAR PACKAGE_29.08.22.pdf",
        "panel_code": "STAR_HEALTH",
        "panel_name": "Star Health Package 2022",
        "parser": "star",
    },
    {
        "path": r"c:\Users\INTEL\Downloads\SAMARITAN CLINIC PVT. LTD._PACKAGE_2023.pdf",
        "panel_code": "HDFC_ERGO",
        "panel_name": "HDFC Ergo Package 2023",
        "parser": "hdfc",
    },
    {
        "path": r"c:\Users\INTEL\Downloads\GALAXY PACKAGE AND SOC_03.07.2025.pdf",
        "panel_code": "GALAXY",
        "panel_name": "Galaxy Health Insurance",
        "parser": "galaxy",
    },
]

CSV_FIELDS = [
    "panel_code",
    "panel_name",
    "procedure_code",
    "procedure_name",
    "speciality",
    "max_stay",
    "package_inclusions",
    "package_exclusions",
    "tier1_code",
    "tier1_rate",
    "tier2_code",
    "tier2_rate",
    "tier3_code",
    "tier3_rate",
    "tier4_code",
    "tier4_rate",
]


def normalize_lcodes(text: str) -> str:
    nums = sorted(set(re.findall(r"L\s*(\d)", text.upper())))
    return ",".join(f"L{n}" for n in nums)


def ocr_pdf(pdf_path: str, reader, panel_code: str = "default") -> str:
    doc = fitz.open(pdf_path)
    chunks = []
    tmp = OUT_DIR / f"_ocr_pages_{panel_code}"
    tmp.mkdir(parents=True, exist_ok=True)
    for i in range(doc.page_count):
        pix = doc[i].get_pixmap(matrix=fitz.Matrix(2, 2))
        img = tmp / f"page_{i+1}.png"
        pix.save(str(img))
        lines = reader.readtext(str(img), detail=0, paragraph=True)
        chunks.append("\n".join(lines))
    doc.close()
    return "\n".join(chunks)


def parse_gipsa_ppn(text: str, panel_code: str, panel_name: str) -> list[dict]:
    """Parse GIPSA / HDFC style PPN rows."""
    rows = []
    text = text.replace("PPN", "PPN ").replace("PPN 6", "PPN G").replace("PPNE", "PPN E")
    pattern = re.compile(
        r"(PPN\s+[A-Z]+\s*\d+(?:\s*[AB])?)\s*([\s\S]*?)(?=PPN\s+[A-Z]+\s*\d+|$)",
        re.IGNORECASE,
    )
    current_speciality = ""

    for m in re.finditer(r"(CARDIOLOGY|ENT|GENERAL SURGERY|OBSTETRICS|GYNE|OPHTHALMOLOGY|ORTHOPAEDICS|ORTHOPEDICS)", text, re.I):
        current_speciality = m.group(1).upper()

    for match in pattern.finditer(text):
        code = re.sub(r"\s+", " ", match.group(1).strip().upper())
        block = match.group(2)
        inclusions = ""
        exclusions = ""
        inc_m = re.search(r"L1[\s,]*L?2?[\s,]*L?3?[\s,]*L?4?", block, re.I)
        if inc_m:
            inclusions = normalize_lcodes(inc_m.group(0))
        if re.search(r"L1\s*,\s*L3\s*,\s*L4", block, re.I) and re.search(r"\bL2\b", block):
            exclusions = "L2"

        rates = [int(x) for x in re.findall(r"\b(\d{4,6})\b", block) if 3000 <= int(x) <= 500000]
        rates = rates[:3]
        if len(rates) < 2:
            continue

        name = ""
        name_m = re.search(
            r"(Angioplasty|Tonsillectomy|Appendectomy|Cholecystectomy|Angiography|CABG|Hernioplasty|Hysterectomy|TKR|Knee|Cataract|Mastoidectomy|FESS|Myringotomy|Haemorrhoidectomy|Hemorrhoidectomy|Delivery|LSCS)[^\n]{0,120}",
            block,
            re.I,
        )
        if name_m:
            name = re.sub(r"\s+", " ", name_m.group(0)).strip()

        row = {
            "panel_code": panel_code,
            "panel_name": panel_name,
            "procedure_code": code,
            "procedure_name": name or code,
            "speciality": current_speciality,
            "max_stay": "",
            "package_inclusions": inclusions,
            "package_exclusions": exclusions,
            "tier1_code": "GEN",
            "tier1_rate": rates[0] if len(rates) > 0 else "",
            "tier2_code": "SEMI",
            "tier2_rate": rates[1] if len(rates) > 1 else "",
            "tier3_code": "PVT",
            "tier3_rate": rates[2] if len(rates) > 2 else "",
            "tier4_code": "",
            "tier4_rate": "",
        }
        rows.append(row)

    # Deduplicate by procedure_code keeping best name
    by_code: dict[str, dict] = {}
    for r in rows:
        c = r["procedure_code"]
        if c not in by_code or len(r["procedure_name"]) > len(by_code[c]["procedure_name"]):
            by_code[c] = r
    return list(by_code.values())


def parse_money(value: str) -> int | None:
    digits = re.sub(r"\D", "", value)
    if digits == "":
        return None
    v = int(digits)
    if 5000 <= v <= 500000:
        return v
    return None


def extract_rates(block: str) -> list[int]:
    rates = []
    for match in re.findall(r"\b(\d[\d,\s]{2,10}\d)\b", block):
        v = parse_money(match)
        if v is not None:
            rates.append(v)
    return rates


def parse_star(text: str, panel_code: str, panel_name: str) -> list[dict]:
    rows = []
    lines = [ln.strip() for ln in text.splitlines() if ln.strip()]
    speciality_headers = {
        "CARDIOLOGY", "ENT", "EN:T", "GENERAL SURGERY", "OBSTETRICS", "GYNAECOLOGY",
        "OPHTHALMOLOGY", "ORTHOPAEDICS", "ORTHOPEDICS", "UROLOGY", "NEUROSURGERY",
    }
    current_speciality = ""

    def is_rate_line(line: str) -> bool:
        return parse_money(line) is not None and re.fullmatch(r"[\d,\s]+", line.strip()) is not None

    i = 0
    while i < len(lines):
        ln = lines[i]
        upper = ln.upper()
        if upper in speciality_headers:
            current_speciality = "ENT" if upper == "EN:T" else upper
            i += 1
            continue
        if any(
            token in upper
            for token in (
                "S.NO", "PROCEDURE NAME", "INCLUSIONS", "EXCLUSTONS", "EXCLUSIONS",
                "PRIVATE", "SEMI-PRIVATE", "SEML-PRIVATE", "GENERAL", "STAR PACKAGE",
                "MAXIMUM DURATION", "SAMARITAN", "WEST BENGAL",
            )
        ):
            i += 1
            continue
        if len(ln) < 8 or not re.search(r"[A-Za-z]{4,}", ln) or is_rate_line(ln):
            i += 1
            continue

        j = i + 1
        inclusions = ""
        exclusions = ""
        rates: list[int] = []
        while j < len(lines) and len(rates) < 3:
            nxt = lines[j]
            if is_rate_line(nxt):
                v = parse_money(nxt)
                if v is not None:
                    rates.append(v)
            elif inclusions == "" and (re.search(r"L\s*\d", nxt, re.I) or "[" in nxt):
                inclusions = normalize_lcodes(nxt.replace("[", "L"))
            elif re.search(r"Exclusions?\s*:?\s*L2", nxt, re.I):
                exclusions = "L2"
            elif len(rates) == 0 and re.search(r"[A-Za-z]{4,}", nxt) and not is_rate_line(nxt):
                break
            j += 1

        if len(rates) >= 2:
            pvt = rates[0]
            semi = rates[1] if len(rates) > 1 else ""
            gen = rates[2] if len(rates) > 2 else ""
            rows.append({
                "panel_code": panel_code,
                "panel_name": panel_name,
                "procedure_code": "",
                "procedure_name": ln[:200],
                "speciality": current_speciality,
                "max_stay": "",
                "package_inclusions": inclusions,
                "package_exclusions": exclusions,
                "tier1_code": "PVT",
                "tier1_rate": pvt,
                "tier2_code": "SEMI",
                "tier2_rate": semi,
                "tier3_code": "GEN",
                "tier3_rate": gen,
                "tier4_code": "",
                "tier4_rate": "",
            })
            i = j
            continue
        i += 1

    return rows[:120]


def parse_hdfc(text: str, panel_code: str, panel_name: str) -> list[dict]:
    speciality_map = {
        "CARDIOLOGY": "CARDIOLOGY",
        "ENT": "ENT",
        "GENERAL SURGERY": "GENERAL SURGERY",
        "OBSTETRICS & GYNE": "OBSTETRICS & GYNAECOLOGY",
        "OPTHALMOLOGY": "OPHTHALMOLOGY",
        "OPHTHALMOLOGY": "OPHTHALMOLOGY",
        "ORTHOPAEDICS": "ORTHOPAEDICS",
        "ORTHOPEDICS": "ORTHOPAEDICS",
    }
    current_speciality = "CARDIOLOGY"
    procedures: list[tuple[str, str, str]] = []

    for m in re.finditer(
        r"(CARDIOLOGY|ENT|GENERAL SURGERY|OBSTETRICS\s*&\s*GYNE|OPTHALMOLOGY|OPHTHALMOLOGY|ORTHOPAEDICS)"
        r"|\b(\d{1,3})\s+([A-Z\(\[][^\n]{8,90}?)(?=\s+\d{1,3}\s+[A-Z\(]|\s+L1|\Z)",
        text,
        re.I,
    ):
        if m.group(1):
            key = re.sub(r"\s+", " ", m.group(1).upper())
            current_speciality = speciality_map.get(key, key)
            continue
        code = m.group(2)
        name = re.sub(r"\s+", " ", m.group(3)).strip(" -.")
        upper_name = name.upper()
        if (
            len(name) < 8
            or upper_name.startswith("L1")
            or "GENERAL SURGERY" in upper_name
            or upper_name.startswith("L1,")
        ):
            continue
        procedures.append((code, name[:200], current_speciality))

    lines = [ln.strip() for ln in text.splitlines() if ln.strip()]
    rate_blocks: list[dict] = []

    def line_rates(line: str) -> list[int]:
        values = []
        for match in re.findall(r"\b(\d[\d,\s]{2,10}\d)\b", line):
            v = parse_money(match)
            if v is not None:
                values.append(v)
        return values

    i = 0
    while i < len(lines):
        ln = lines[i]
        if re.fullmatch(r"L2", ln.strip(), re.I):
            i += 1
            continue

        if re.search(r"L\s*\d", ln, re.I) or "[" in ln:
            inclusions = normalize_lcodes(ln.replace("[", "L"))
            exclusions = "L2" if re.search(r"\bL2\b", ln) else ""
            rates: list[int] = []
            j = i + 1
            while j < len(lines) and len(rates) < 3:
                nxt = lines[j]
                if nxt.strip() == "L2":
                    exclusions = "L2"
                elif line_rates(nxt):
                    rates.extend(line_rates(nxt))
                    if len(rates) >= 3:
                        break
                elif re.search(r"[A-Za-z]{4,}", nxt) and not re.search(r"L\s*\d", nxt, re.I):
                    break
                j += 1
            rates = rates[:3]
            if len(rates) >= 2:
                rate_blocks.append({
                    "package_inclusions": inclusions,
                    "package_exclusions": exclusions,
                    "rates": rates,
                })
                i = j
                continue

        row_rates = line_rates(ln)
        if len(row_rates) >= 3:
            matrix = [row_rates]
            j = i + 1
            while j < len(lines):
                nxt_rates = line_rates(lines[j])
                if len(nxt_rates) == len(row_rates):
                    matrix.append(nxt_rates)
                    j += 1
                else:
                    break
            if len(matrix) >= 2:
                width = len(row_rates)
                for col in range(width):
                    gen = matrix[0][col]
                    semi = matrix[1][col] if len(matrix) > 1 else gen
                    pvt = matrix[2][col] if len(matrix) > 2 else semi
                    rate_blocks.append({
                        "package_inclusions": "",
                        "package_exclusions": "",
                        "rates": [gen, semi, pvt],
                    })
                i = j
                continue
        i += 1

    rows = []
    for idx, (code, name, speciality) in enumerate(procedures):
        if idx >= len(rate_blocks):
            break
        block = rate_blocks[idx]
        rates = block["rates"]
        rows.append({
            "panel_code": panel_code,
            "panel_name": panel_name,
            "procedure_code": f"HDFC-{code}",
            "procedure_name": name,
            "speciality": speciality,
            "max_stay": "",
            "package_inclusions": block["package_inclusions"],
            "package_exclusions": block["package_exclusions"],
            "tier1_code": "GEN",
            "tier1_rate": rates[0],
            "tier2_code": "SEMI",
            "tier2_rate": rates[1] if len(rates) > 1 else "",
            "tier3_code": "PVT",
            "tier3_rate": rates[2] if len(rates) > 2 else "",
            "tier4_code": "",
            "tier4_rate": "",
        })

    return rows[:150]


def parse_galaxy(text: str, panel_code: str, panel_name: str) -> list[dict]:
    rows = []
    lines = [ln.strip() for ln in text.splitlines() if ln.strip()]
    skip_tokens = (
        "GALAXY", "ROHINI", "SAMARITAN", "HOSPITAL", "SEAL", "SIGNATURE",
        "OCCUPANCY", "PROCEDURE NAME", "DURATION", "S.NO", "WARD ROOM",
        "MULTISPECIALITY", "SURGICAL PACKAGE", "AUTHORITY",
    )
    current_sn = 0

    def is_rate_line(line: str) -> bool:
        return parse_money(line) is not None and re.fullmatch(r"[\d,\s]+", line.strip()) is not None

    def make_row(sn: int, name: str, rates: list[int]) -> dict:
        return {
            "panel_code": panel_code,
            "panel_name": panel_name,
            "procedure_code": f"GAL-{sn}",
            "procedure_name": re.sub(r"\s+", " ", name).strip()[:200],
            "speciality": "",
            "max_stay": "",
            "package_inclusions": "",
            "package_exclusions": "",
            "tier1_code": "DLX",
            "tier1_rate": rates[0],
            "tier2_code": "SHR",
            "tier2_rate": rates[1] if len(rates) > 1 else "",
            "tier3_code": "GW",
            "tier3_rate": rates[2] if len(rates) > 2 else "",
            "tier4_code": "",
            "tier4_rate": "",
        }

    i = 0
    while i < len(lines):
        ln = lines[i]
        upper = ln.upper()
        if any(token in upper for token in skip_tokens):
            i += 1
            continue

        if re.fullmatch(r"\d{1,3}", ln):
            current_sn = int(ln)
            i += 1
            continue

        numbered = re.match(r"^(\d{1,3})\s+(.+)$", ln)
        if numbered and re.search(r"[A-Za-z]{4,}", numbered.group(2)):
            current_sn = int(numbered.group(1))
            name = numbered.group(2).strip()
            i += 1
            rates: list[int] = []
            while i < len(lines) and len(rates) < 3:
                if is_rate_line(lines[i]):
                    v = parse_money(lines[i])
                    if v is not None:
                        rates.append(v)
                    i += 1
                else:
                    break
            if len(rates) >= 2 and current_sn > 0:
                rows.append(make_row(current_sn, name, rates))
            continue

        if len(ln) >= 8 and re.search(r"[A-Za-z]{4,}", ln) and not is_rate_line(ln):
            name = ln
            j = i + 1
            rates = []
            while j < len(lines) and len(rates) < 3:
                nxt = lines[j]
                if is_rate_line(nxt):
                    v = parse_money(nxt)
                    if v is not None:
                        rates.append(v)
                    j += 1
                elif re.fullmatch(r"\d{1,3}", nxt) or re.match(r"^\d{1,3}\s+[A-Za-z]", nxt):
                    break
                elif re.search(r"[A-Za-z]{4,}", nxt) and not is_rate_line(nxt):
                    name += " " + nxt
                    j += 1
                else:
                    break
            if len(rates) >= 2:
                current_sn = current_sn + 1 if current_sn == 0 else current_sn + 1
                if current_sn == 0:
                    current_sn = len(rows) + 1
                rows.append(make_row(current_sn, name, rates))
                i = j
                continue
        i += 1

    # Deduplicate by procedure code keeping longest name
    by_code: dict[str, dict] = {}
    for row in rows:
        code = row["procedure_code"]
        if code not in by_code or len(row["procedure_name"]) > len(by_code[code]["procedure_name"]):
            by_code[code] = row

    return list(by_code.values())[:200]


def write_csv(path: Path, rows: list[dict]) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    with path.open("w", newline="", encoding="utf-8") as f:
        w = csv.DictWriter(f, fieldnames=CSV_FIELDS)
        w.writeheader()
        for r in rows:
            w.writerow({k: r.get(k, "") for k in CSV_FIELDS})


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--pdf", help="Single PDF path")
    parser.add_argument("--panel", help="Panel code e.g. GIPSA_PPN")
    parser.add_argument("--name", help="Panel display name")
    parser.add_argument("--parser", choices=["gipsa", "star", "hdfc", "galaxy"], default="gipsa")
    parser.add_argument("--skip-ocr", action="store_true", help="Parse from existing *_raw.txt instead of re-OCR")
    args = parser.parse_args()

    reader = None
    if not args.skip_ocr:
        print("Loading EasyOCR (first run may download models)...")
        reader = easyocr.Reader(["en"], gpu=False, verbose=False)

    jobs = DEFAULT_PDFS
    if args.pdf:
        jobs = [{
            "path": args.pdf,
            "panel_code": args.panel or "CUSTOM_PANEL",
            "panel_name": args.name or args.panel or "Custom Panel",
            "parser": args.parser,
        }]

    all_rows = []
    for job in jobs:
        pdf = job["path"]
        raw_path = OUT_DIR / f"{job['panel_code']}_raw.txt"
        if args.skip_ocr:
            if not raw_path.exists():
                print(f"SKIP (no raw OCR): {job['panel_code']}")
                continue
            print(f"Parse raw: {raw_path}")
            text = raw_path.read_text(encoding="utf-8")
        else:
            if not os.path.exists(pdf):
                print(f"SKIP (not found): {pdf}")
                continue
            print(f"OCR: {pdf}")
            text = ocr_pdf(pdf, reader, job["panel_code"])
            raw_path.write_text(text, encoding="utf-8")
            print(f"  Raw OCR saved: {raw_path}")

        if job["parser"] == "star":
            rows = parse_star(text, job["panel_code"], job["panel_name"])
        elif job["parser"] == "hdfc":
            rows = parse_hdfc(text, job["panel_code"], job["panel_name"])
        elif job["parser"] == "galaxy":
            rows = parse_galaxy(text, job["panel_code"], job["panel_name"])
        else:
            rows = parse_gipsa_ppn(text, job["panel_code"], job["panel_name"])

        out_csv = OUT_DIR / f"{job['panel_code']}.csv"
        write_csv(out_csv, rows)
        print(f"  Wrote {len(rows)} rows -> {out_csv}")
        all_rows.extend(rows)

    combined = OUT_DIR / "all_insurance_packages.csv"
    write_csv(combined, all_rows)
    print(f"\nCombined: {len(all_rows)} rows -> {combined}")
    print("Import with: php artisan insurance:import-packages database/imports/insurance_packages/all_insurance_packages.csv --replace")


if __name__ == "__main__":
    main()
