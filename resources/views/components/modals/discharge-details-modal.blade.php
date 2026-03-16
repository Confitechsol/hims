  <style>
      .first_logo {
          font-size: 12px;
          width: 29%;
          position: absolute;
          background-color: #fff;
          padding-right: 20px;
      }

      .first_logo img {
          margin: 19px 0px;
          border-left: 5px solid #3b6c7b;
          padding-left: 10px;
      }

      .about_info {
          font-size: 12px;
      }

      .wreeti {
          font-size: 10px;
      }

      .main_box {
          max-width: 1199px;
          box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
          margin: 20px auto;
          width: 210mm;
          /* height: 297mm; */
          height: auto;
          min-height: 297mm;
          line-height: 10px;
      }

      .body_area {
          padding: 0 20px 20px;
          /* background-image: url("{{ asset('/assets/images/body.webp') }}");
          background-position: center;
          background-repeat: no-repeat;
          background-size: 100% 100%; */
          height: 100%;
      }

      .body_area {
          -webkit-print-color-adjust: exact !important;
          print-color-adjust: exact !important;
      }

      .top_head {
          display: flex;
          align-items: center;
          justify-content: space-between;
          /* padding: 0 0px 20px 20px; */
          position: relative;
      }

      .about_info {
          text-align: end;
          line-height: 0.5;
          background-color: #3b6c7b;
          color: #fff;
          padding: 5px 15px;
          width: 100%;
      }

      .heading1 h4 {
          text-align: center;
          font-size: 17px;
          text-transform: uppercase;
          padding: 10px 5px;
          color: #7d7c7c;
          text-decoration: underline;
      }

      .heading h4 {
          text-transform: uppercase;
          text-align: center;
          margin: 10px auto;
          font-size: 15px;
      }

      .red {
          color: #ff3405;
          font-weight: 700;
      }

      .admission_info {
          display: flex;
          align-items: center;
          justify-content: space-between;
          flex-wrap: wrap;
          /* padding: 0 12px; */
          font-size: 10px;
      }

      .patient_info {
          border: 2px solid #9c9c9c;
          display: flex;
          padding: 5px;
          margin-bottom: 1px;
          font-weight: 700;
          font-size: 10px;
          column-gap: 30px;
      }

      .patient_details {
          width: 50%;
      }

      /* .patient_items {
          display: flex;
          align-items: center;
          gap: 20px;
          margin-bottom: 4px;
      } */

      .patient_items {
          display: grid;
          grid-template-columns: 120px 10px auto;
          align-items: center;
          margin-bottom: 4px;
          font-size: 10px;
      }

      .patient_head {
          width: 80px;
          font-weight: 700;
      }

      .colon {
          text-align: center;
          font-weight: 700;
      }

      /* .patient_data {
          word-break: break-word;
      } */

      .patient_box {
          display: flex;
          align-items: center;
          gap: 30px;
      }

      .general_list li {
          margin-bottom: 10px;
      }

      .bottom_box {
          /* border-bottom: 2px dashed #282828; */
          display: flex;
          align-items: end;
          justify-content: space-between;
          font-size: 10px;
          margin-top: 1rem;
      }

      .contact_box {
          width: 80%;
      }


      .d-signature {
          height: 40px;
          max-height: 50px;
          width: 200px;
      }

      .sig_box {
          border-top: 2px solid #9c9c9c;
          width: 100%;
      }

      .blue {
          color: #010080;
          font-weight: 700;
      }

      .wreeti_items {
          font-weight: 700;
          margin-bottom: 8px;
      }

      .wreeti_box {
          display: flex;
          align-items: center;
          gap: 50px;
      }

      .wreeti_sig {
          display: flex;
          align-items: end;
          gap: 10px;
          font-size: 10px;
          margin-top: 30px;
      }

      .wreeti_sig p {
          margin-bottom: 0;
      }

      .line {
          border: 1px solid #282828;
          width: 40%;
      }

      .text {
          font-size: 10px;
          border: 2px solid #9c9c9c;
          padding: 10px;
      }

      .text h4 {
          font-size: 10px;
      }

      .admission_item {
          width: 30%;
      }

      .end {
          border-top: 1px solid #9c9c9c;
          text-align: center;
      }

      /* img {
          width: 100%;
      } */

      .text-end {
          text-align: end;
          padding-right: 15px;
      }

      .document-wrapper {
          height: 100%;
          min-height: inherit;
      }

      .document-container {
          /* display: grid;
          grid-template-rows: auto 1fr auto;
          height: 100%; */
          display: flex;
          flex-direction: column;
          height: 100%;
          min-height: inherit;
      }

      .footer {
          width: 100%;
      }

      .hide-header .header,
      .hide-footer .footer {
          visibility: hidden;
      }

      /* Keep space */
      /* .header,
      .footer {
          min-height: 120px;
      } */
      .toggle-switch {
          cursor: pointer;
      }
  </style>
  <div class="modal fade" id="dischargePreviewModal" tabindex="-1">
      <div class="modal-dialog modal-fullscreen">
          <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                  <h5 class="modal-title">
                      <i class="bi bi-file-earmark-medical"></i>
                      Discharge Summary Preview
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <!-- Modal Body -->
              <div class="modal-body">
                  <div class="main_box">
                      <div class="document-wrapper">
                          <div class="document-container" id="dischargeSummary">
                              <div class="header">
                                  <img src="{{ asset('assets/images/header.webp') }}" alt="header" />
                              </div>

                              <div class="body_area flex-grow-1">
                                  <div class="heading1">
                                      <h4>DISCHARGE SUMMARY & CERTIFICATE</h4>
                                  </div>

                                  <div class="admission_info mb-3">
                                      <div class="admission_item">
                                          <p><b>DISCHARGE NO.</b> : <span class="red" id="dis_discharge_no"></span>
                                          </p>
                                      </div>
                                      <div class="admission_item heading">
                                          <h4 id="dis_discharge_type_head"></h4>
                                      </div>
                                      <div class="admission_item heading">
                                          <img id="dis_barcode" class="img-fluid rounded shadow-sm"
                                              style="max-height:50px; height:50px; min-width:120px; object-fit:cover;">
                                      </div>
                                  </div>

                                  <div class="patient_info">
                                      <div class="patient_details">
                                          <div class="patient_items">
                                              <div class="patient_head">PATIENT NAME</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="dis_patient_name_text"></div>
                                          </div>

                                          <div class="patient_items">
                                              <div class="patient_head">ADDRESS</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="dis_patient_address_text">
                                              </div>
                                          </div>

                                          <div class="patient_items">
                                              <div class="patient_head">Admission Date</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data w-auto" id="dis_admission_date_text"></div>
                                          </div>
                                          <div class="patient_items">
                                              <div class="patient_head">Admission Time</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data w-auto" id="dis_admission_time_text"></div>
                                          </div>
                                          <div class="patient_items" id="ot-date">
                                              <div class="patient_head">OT Date</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="dis_ot_date_text"></div>
                                          </div>
                                          <div class="patient_items">
                                              <div class="patient_head">Admission No.</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="dis_admission_no_text"></div>
                                          </div>
                                      </div>

                                      <div class="patient_details">

                                          <div class="patient_items">
                                              <div class="patient_head">Sex</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="dis_gender_text"></div>
                                          </div>
                                          <div class="patient_items">
                                              <div class="patient_head">Age</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="dis_age_text"></div>
                                          </div>


                                          <div class="patient_items">
                                              <div class="patient_head">Contact No</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="dis_mobile_text"></div>
                                          </div>
                                          <div class="patient_items">
                                              <div class="patient_head">Discharge Date</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data w-auto" id="dis_discharge_date_text"></div>
                                          </div>
                                          <div class="patient_items">
                                              <div class="patient_head">Discharge Time</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data w-auto" id="dis_discharge_time_text"></div>
                                          </div>
                                          {{-- <div class="patient_items">
                                              <div class="patient_head">Discharge Type</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="discharge_type_text"></div>
                                          </div> --}}
                                          {{-- <div class="patient_items">
                                              <div class="patient_head">Discharge Contact No.</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="dis_contact_no_text"></div>
                                          </div> --}}
                                          <div class="patient_items">
                                              <div class="patient_head">BED</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="dis_bed_text"></div>
                                          </div>

                                      </div>
                                  </div>

                                  <div class="patient_info">
                                      <div class="patient_details">
                                          <div class="patient_items">
                                              <div class="patient_head">Under Care Dr.</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="dis_under_care_text"></div>
                                          </div>
                                      </div>

                                      <div class="patient_details">
                                          <div class="patient_items">
                                              <div class="patient_head">Registration No.</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="dis_registration_no_text"></div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="text">

                                      <h6 id="dis_present_complains_label">Present Complains :</h6>
                                      <div class="general_list" id="dis_present_complains_html"></div>

                                      <br />

                                      <h6 id="dis_diagnosis_label">Diagnosis :</h6>
                                      <div class="general_list" id="dis_diagnosis_html"></div>

                                      <br />

                                      <h6 id="dis_ot_note_label">Treatment Done/Procedure Performed / OT Note :</h6>
                                      <div class="general_list" id="dis_ot_note_html"></div>

                                      <br />

                                      <h6 id="dis_course_in_hospital_label">Course in Hospital :</h6>
                                      <div class="general_list" id="dis_course_in_hospital_html"></div>
                                      <br />


                                      <h6 id="discharge_medicine_label">Discharge Advised Medicines:</h6>
                                      <div class="general_list" id="discharge_medicine_list"></div>
                                      <br />
                                      <h6 id="dis_investigation_label">Investigations:</h6>
                                      <div class="general_list" id="dis_investigation_html"></div>
                                      <br />
                                      <h6 id="dis_urgent_care_label">Urgent Care Instructions:</h6>
                                      <div class="general_list" id="dis_urgent_care_html"></div>
                                      <br />
                                      <h6 id="dis_diet_advice_label">Diet Advice:</h6>
                                      <div class="general_list" id="dis_diet_advice_html"></div>
                                      <br />

                                      <h6 id="dis_discharge_advice_label">Condition at Discharge:</h6>
                                      <div class="general_list" id="dis_discharge_advice_html"></div>
                                      <br />
                                      <h6 id="dis_remarks_label">Follow Up:</h6>
                                      <div class="general_list" id="dis_remarks_text"></div>
                                      <br />
                                      <div class="end">
                                          {{-- <p>---- xxxx -- END -- xxxx ---</p> --}}
                                      </div>

                                      <div class="bottom_box">
                                          <div class="contact_box">
                                              <p>DATE : {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
                                          </div>
                                          <div class="d-flex flex-column align-items-center">

                                              @php
                                                  $signature = $ipd->doctor->signature ?? null;
                                                  $signaturePath = public_path(
                                                      'uploads/Doctor/signatures/' . $signature,
                                                  );
                                              @endphp

                                              @if (!empty($signature) && file_exists($signaturePath))
                                                  <img class="d-signature"
                                                      src="{{ asset('uploads/Doctor/signatures/' . $signature) }}"
                                                      alt="Doctor Signature">
                                              @else
                                                  <p class="fw-bold mb-2" style="font-size: small;">
                                                      {{ $ipd->doctor->name }}</p>
                                              @endif

                                              <div class="sig_box text-center">
                                                  <p>Signature of Doctor / R.M.O</p>
                                                  @if (!empty($signature) && file_exists($signaturePath))
                                                      <p class="mb-2 fw-bold">Doctor : {{ $ipd->doctor->name }}</p>
                                                  @endif
                                                  <p>Regn No : {{ $ipd->doctor->registration_no ?? '' }}</p>
                                              </div>

                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <div class="footer">
                                  <img src="{{ asset('assets/images/footer.webp') }}" alt="footer" />
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer pb-0">
                  <div class="d-flex align-items-center gap-2 ms-auto me-3">
                      {{-- <button type="button" class="btn btn-outline-secondary" onclick="downloadDocument()">
                          <i class="bi bi-download"></i>
                          Download
                      </button> --}}
                      <div class="form-check form-switch toggle-switch">
                          <input class="form-check-input toggle-switch" type="checkbox" role="switch"
                              id="toggleHeaderFooter" checked>
                          <label class="form-check-label toggle-switch" for="toggleHeaderFooter">Print Header &
                              Footer</label>
                      </div>
                      {{-- <a href="{{ route('discharge.pdf', $ipd->id) }}" target="_blank" class="btn btn-primary">
                          <i class="bi bi-printer"></i> Print
                      </a> --}}
                      <button type="button" class="btn btn-primary" onclick="openPdf({{ $ipd->id }})">
                          <i class="bi bi-printer"></i>
                          Print / Download
                      </button>
                  </div>
              </div>
          </div>
      </div>
  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

  <script>
      document.getElementById('dischargePreviewModal')
          .addEventListener('show.bs.modal', function(event) {

              const button = event.relatedTarget;
              const data = JSON.parse(button.getAttribute('data-discharge'));
              const medicines = JSON.parse(button.getAttribute('data-medicines'));
              console.log(data);

              if (!data) return;

              /* -------------------------
                 SIMPLE TEXT FIELDS
              ------------------------- */

              setText('dis_discharge_type_head', data.reason_discharge);
              setText('dis_discharge_no', data.discharge_number);
              setText('dis_patient_name_text', data.patient_name);
              setText('dis_patient_address_text', data.address);
              setText('dis_admission_no_text', data.admission_no);
              setText('dis_gender_text', data.gender);
              setText('dis_age_text', data.age);
              setText('dis_mobile_text', data.phone);
              setText('dis_bed_text', data.bed);
              setText('dis_contact_no_text', data.discharge_contact);
              setText('dis_under_care_text', data.under_care_dr);
              setText('dis_registration_no_text', data.registration_no);
              //   setText('discharge_type_text', data.reason_discharge);

              /* -------------------------
                 DATE & TIME HANDLING
              ------------------------- */
              setText('dis_admission_date_text', formatDate(new Date(data.admission_date)));
              setText('dis_admission_time_text', data.admit_time);

              setText('dis_discharge_date_text', formatDate(new Date(data.discharge_date)));
              setText('dis_discharge_time_text', data.discharge_time);

              if (data.ot_date) {
                  setText('dis_ot_date_text', formatDate(new Date(data.ot_date)));
              } else {
                  hideLabel('ot-date');
              }

              if (medicines.length > 0) {
                  setOrderedList('discharge_medicine_list', medicines)
              } else {
                  hideLabel('discharge_medicine_label')
              }

              /* -------------------------
                 CKEDITOR (HTML) FIELDS
              ------------------------- */
              if (data.diagnosis === "" || data.diagnosis === null) {
                  hideLabel('dis_diagnosis_label')
              } else {
                  setHTML('dis_diagnosis_html', data.diagnosis);
              }

              if (data.course_in_hospital === "" || data.course_in_hospital === null) {
                  hideLabel('dis_course_in_hospital_label')
              } else {
                  setHTML('dis_course_in_hospital_html', data.course_in_hospital);
              }
              if (data.investigation === "" || data.investigation === null) {
                  hideLabel('dis_investigation_label')
              } else {
                  setHTML('dis_investigation_html', data.investigation);
              }
              if (data.urgent_care === "" || data.urgent_care === null) {
                  hideLabel('dis_urgent_care_label')
              } else {
                  setHTML('dis_urgent_care_html', data.urgent_care);
              }
              if (data.diet_advice === "" || data.diet_advice === null) {
                  hideLabel('dis_diet_advice_label')
              } else {
                  setHTML('dis_diet_advice_html', data.diet_advice);
              }

              if (data.ot_note === "" || data.ot_note === null) {
                  hideLabel('dis_ot_note_label')
              } else {
                  setHTML('dis_ot_note_html', data.ot_note);
              }

              if (data.discharge_advice === "" || data.discharge_advice === null) {
                  hideLabel('dis_discharge_advice_label')
              } else {
                  setHTML('dis_discharge_advice_html', data.discharge_advice);
              }

              if (data.remarks === "" || data.remarks === null) {
                  hideLabel('dis_remarks_label')
              } else {
                  setText('dis_remarks_text', data.remarks);
              }

              if (data.present_complaints === "" || data.present_complaints === null) {
                  hideLabel('dis_present_complains_label')
              } else {
                  setHTML('dis_present_complains_html', data.present_complaints);
              }

              setImage('dis_barcode', data.barcode);
          });

      function hideLabel(id) {
          const el = document.getElementById(id);
          if (!el) {
              console.warn(`Element not found: ${id}`);
              return;
          }
          if (el) el.style.display = "none" ?? '';
      }

      function setText(id, value) {
          const el = document.getElementById(id);
          if (!el) {
              console.warn(`Element not found: ${id}`);
              return;
          }
          if (el) el.textContent = value ?? '';
      }

      function setOrderedList(id, items = []) {
          const el = document.getElementById(id);

          if (!el) {
              console.warn(`Element not found: ${id}`);
              return;
          }

          el.innerHTML = '';

          if (!Array.isArray(items) || !items.length) {
              el.textContent = '—';
              return;
          }

          const ol = document.createElement('ol');
          ol.style.paddingLeft = '2rem'; // optional bootstrap spacing

          items.forEach(item => {
              if (item && String(item).trim() !== '') {
                  const li = document.createElement('li');
                  li.textContent = item;
                  ol.appendChild(li);
              }
          });

          el.appendChild(ol);
      }


      function setHTML(id, html) {
          const el = document.getElementById(id);
          if (!el) {
              console.warn(`Element not found: ${id}`);
              return;
          }

          // Clear previous content
          el.innerHTML = '';

          if (!html) return;

          // CKEditor content already contains <ul><li>
          el.innerHTML = html;
      }

      function formatDate(date) {
          const dd = String(date.getDate()).padStart(2, '0');
          const mm = String(date.getMonth() + 1).padStart(2, '0');
          const yyyy = date.getFullYear();
          return `${dd}-${mm}-${yyyy}`;
      }

      function setImage(id, src) {
          const img = document.getElementById(id);
          if (!img) return;

          if (src) {
              img.src = src;
              img.style.display = 'block';
          } else {
              img.style.display = 'none';
          }
      }
  </script>
  <script>
      document.getElementById('toggleHeaderFooter')
          .addEventListener('change', function() {

              const container = document.getElementById('dischargeSummary');

              if (this.checked) {
                  container.classList.remove('hide-header', 'hide-footer');
              } else {
                  container.classList.add('hide-header', 'hide-footer');
              }
          });
  </script>
  <script>
      function openPdf(id) {
          const baseUrl = "{{ route('discharge.pdf', ['id' => 'ID']) }}";
          const withHF = document.getElementById('toggleHeaderFooter').checked ? 1 : 0;

          const finalUrl = baseUrl.replace('ID', id) + '?hf=' + withHF;
          window.open(finalUrl, '_blank');
      }


      function downloadDocument() {
          const element = document.getElementById('dischargeSummary');

          const opt = {
              margin: 0,
              filename: 'Discharge_Summary.pdf',
              image: {
                  type: 'jpeg',
                  quality: 1
              },
              html2canvas: {
                  scale: 3,
                  useCORS: true,
                  allowTaint: true,
                  backgroundColor: '#ffffff'
              },
              jsPDF: {
                  unit: 'mm',
                  format: 'a4',
                  orientation: 'portrait'
              }
          };

          html2pdf().set(opt).from(element).save();
      }
  </script>
