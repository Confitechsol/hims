<?php

namespace App\Support;

use App\Models\InsuranceRatePanel;

/**
 * Insurer-specific room tier codes for package rates and bed-group mappings.
 *
 * L1–L4 in GIPSA/Star/HDFC PDFs are package inclusion buckets (not room tiers).
 * Room tiers in those contracts are General / Semi-Private / Private.
 */
class InsurerRoomTierPresets
{
    public const SCHEME_GIPSA = 'gipsa';
    public const SCHEME_STAR = 'star';
    public const SCHEME_HDFC = 'hdfc';
    public const SCHEME_GALAXY = 'galaxy';
    public const SCHEME_ICICI = 'icici';

    /**
     * @return array<int, array{code: string, label: string}>
     */
    public static function tiersForScheme(string $scheme): array
    {
        return match ($scheme) {
            self::SCHEME_GALAXY => [
                ['code' => 'GW', 'label' => 'General Ward'],
                ['code' => 'SHR', 'label' => 'Sharing / Single Non-AC'],
                ['code' => 'DLX', 'label' => 'Deluxe / Single AC'],
            ],
            self::SCHEME_ICICI => [
                ['code' => 'A', 'label' => 'Room tier A'],
                ['code' => 'B', 'label' => 'Room tier B'],
                ['code' => 'C', 'label' => 'Room tier C'],
                ['code' => 'D', 'label' => 'Room tier D'],
            ],
            default => [
                ['code' => 'GEN', 'label' => 'General'],
                ['code' => 'SEMI', 'label' => 'Semi-Private'],
                ['code' => 'PVT', 'label' => 'Private'],
            ],
        };
    }

    public static function detectScheme(?InsuranceRatePanel $panel): string
    {
        if (!$panel) {
            return self::SCHEME_GIPSA;
        }

        $haystack = strtoupper(trim(($panel->code ?? '') . ' ' . ($panel->name ?? '')));

        if (str_contains($haystack, 'GALAXY')) {
            return self::SCHEME_GALAXY;
        }
        if (str_contains($haystack, 'ICICI')) {
            return self::SCHEME_ICICI;
        }
        if (str_contains($haystack, 'STAR')) {
            return self::SCHEME_STAR;
        }
        if (str_contains($haystack, 'HDFC') || str_contains($haystack, 'ERGO')) {
            return self::SCHEME_HDFC;
        }
        if (str_contains($haystack, 'GIPSA') || str_contains($haystack, 'PPN')) {
            return self::SCHEME_GIPSA;
        }

        return self::SCHEME_GIPSA;
    }

    /**
     * @return array{scheme: string, scheme_label: string, tiers: array<int, array{code: string, label: string}>, inclusion_legend: array<string, string>}
     */
    public static function forPanel(?InsuranceRatePanel $panel): array
    {
        $scheme = self::detectScheme($panel);

        return [
            'scheme' => $scheme,
            'scheme_label' => self::schemeLabel($scheme),
            'tiers' => self::tiersForScheme($scheme),
            'inclusion_legend' => self::inclusionLegend(),
        ];
    }

    public static function schemeLabel(string $scheme): string
    {
        return match ($scheme) {
            self::SCHEME_GALAXY => 'Galaxy (GW / SHR / DLX)',
            self::SCHEME_ICICI => 'ICICI Lombard (A / B / C / D)',
            self::SCHEME_STAR => 'Star Health (GEN / SEMI / PVT)',
            self::SCHEME_HDFC => 'HDFC Ergo (GEN / SEMI / PVT)',
            default => 'GIPSA PPN (GEN / SEMI / PVT)',
        };
    }

    /**
     * Standard GIPSA / Star / HDFC inclusion bucket definitions (L1–L4).
     *
     * @return array<string, string>
     */
    public static function inclusionLegend(): array
    {
        return [
            'L1' => 'Doctor fee, OT, anesthesia, drugs, investigations, professional charges, room rent, nursing, admin',
            'L2' => 'IOL, pacemaker, ortho prosthesis, stents, staplers, guidewire, balloon, catheter',
            'L3' => 'Assays, high-end hormonal studies, SPECT, A scans, etc.',
            'L4' => 'Laparoscopy / abdominal / vaginal / laser procedures',
        ];
    }

    /**
     * @return array<string, array{scheme: string, scheme_label: string, tiers: array<int, array{code: string, label: string}>}>
     */
    public static function allSchemesForClient(): array
    {
        $out = [];
        foreach ([self::SCHEME_GIPSA, self::SCHEME_STAR, self::SCHEME_HDFC, self::SCHEME_GALAXY, self::SCHEME_ICICI] as $scheme) {
            $out[$scheme] = [
                'scheme' => $scheme,
                'scheme_label' => self::schemeLabel($scheme),
                'tiers' => self::tiersForScheme($scheme),
            ];
        }

        return $out;
    }
}
