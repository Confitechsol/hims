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
          height: 297mm;
          min-height: 297mm;
          line-height: 10px;
      }

      .body_area {
          padding: 0 20px 20px;
          background-image: url("{{ asset('/assets/images/body.webp') }}");
          background-position: center;
          background-repeat: no-repeat;
          background-size: 100% 100%;
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
          margin-top: 35px;
      }

      .contact_box {
          width: 80%;
      }

      .sig_box {
          border-top: 2px solid #9c9c9c;
          width: 20%;
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
      }

      .document-container {
          display: grid;
          grid-template-rows: auto 1fr auto;
          height: 100%;
      }

      .footer {
          width: 100%;
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

                              <div class="body_area">
                                  <div class="heading1">
                                      <h4>DISCHARGE SUMMARY & CERTIFICATE</h4>
                                  </div>

                                  <div class="admission_info mb-3">
                                      <div class="admission_item">
                                          <p><b>DISCHARGE NO.</b> : <span class="red" id="discharge_no"></span></p>
                                      </div>
                                      <div class="admission_item heading">
                                          <h4 id="discharge_type_head"></h4>
                                      </div>
                                      <div class="admission_item heading">
                                          <img id="barcode" class="img-fluid rounded shadow-sm"
                                              style="max-height:50px; height:50px; min-width:120px; object-fit:cover;">
                                      </div>
                                  </div>

                                  <div class="patient_info">
                                      <div class="patient_details">
                                          <div class="patient_items">
                                              <div class="patient_head">PATIENT NAME</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="patient_name_text"></div>
                                          </div>

                                          <div class="patient_items">
                                              <div class="patient_head">ADDRESS</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="patient_address_text">
                                              </div>
                                          </div>

                                          <div class="patient_items">
                                              <div class="patient_head">Admission Date</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data w-auto" id="admission_date_text"></div>
                                          </div>
                                          <div class="patient_items">
                                              <div class="patient_head">Admission Time</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data w-auto" id="admission_time_text"></div>
                                          </div>
                                          <div class="patient_items">
                                              <div class="patient_head">OT Date</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="ot_date_text"></div>
                                          </div>
                                          <div class="patient_items">
                                              <div class="patient_head">Admission No.</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="admission_no_text"></div>
                                          </div>
                                      </div>

                                      <div class="patient_details">

                                          <div class="patient_items">
                                              <div class="patient_head">Sex</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="gender_text"></div>
                                          </div>
                                          <div class="patient_items">
                                              <div class="patient_head">Age</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="age_text"></div>
                                          </div>


                                          <div class="patient_items">
                                              <div class="patient_head">Contact No</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="mobile_text"></div>
                                          </div>
                                          <div class="patient_items">
                                              <div class="patient_head">Discharge Date</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data w-auto" id="discharge_date_text"></div>
                                          </div>
                                          <div class="patient_items">
                                              <div class="patient_head">Discharge Time</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data w-auto" id="discharge_time_text"></div>
                                          </div>
                                          {{-- <div class="patient_items">
                                              <div class="patient_head">Discharge Type</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="discharge_type_text"></div>
                                          </div> --}}
                                          <div class="patient_items">
                                              <div class="patient_head">BED</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="bed_text"></div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="patient_info">
                                      <div class="patient_details">
                                          <div class="patient_items">
                                              <div class="patient_head">Under Care</div>
                                              <div class="colon">:</div>
                                              <div class="patient_data" id="under_care_text"></div>
                                          </div>
                                      </div>

                                      <div class="patient_details">
                                          <div class="patient_items">
                                              <div class="patient_data" id="registration_no_text"></div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="text">

                                      <h4>Present Complains :</h4>
                                      <div class="general_list" id="present_complains_html"></div>

                                      <br />

                                      <h4>Treatment Done/Procedure Performed / OT Note :</h4>
                                      <div class="general_list" id="ot_note_html"></div>

                                      <br />

                                      <h4>Diagnosis :</h4>
                                      <div class="general_list" id="diagnosis_html"></div>

                                      <br />

                                      {{-- <p>Follow Up date: Review after 14 - 12 days</p>
                                      <p>Condition at Discharge: Haemodynamically Stable.</p>
                                      <p>Investigation : Investigation done before admission.</p>
                                      <p>Course in Hospital : IV and Oral medication.</p>
                                      <p>When and how to obtain urgent care : SOS/ER</p>
                                      <p>DIET ADVICE: Semi-Solid Diet.</p> --}}

                                      {{-- <br /> --}}

                                      <h4>Discharge Advice:</h4>
                                      <div class="general_list" id="discharge_advice_html"></div>
                                      {{-- <ol > --}}
                                      {{-- <li>TAB CEFAKIND (500) 1 TAB BDPC X 5 DAYS</li>
                                          <li>TAB PARASAFE (650) 1 TAB QDS X 2 WEEKS</li>
                                          <li>TAB PANTOP (40) 1TAB ODAC X 2 WEEKS</li>
                                          <li>CAP ARISTOZYME 1 CAP BD X2 MONTHS</li>
                                          <li>SYP EMTY 2 TSF ODHS X 2 WEEKS</li>
                                          <li>Change of dressing after 10 - 12 days at clinic.</li> --}}
                                      {{-- </ol> --}}

                                      <div class="end">
                                          <p>---- xxxx -- END -- xxxx ---</p>
                                      </div>

                                      <div class="bottom_box">
                                          <div class="contact_box">
                                              <p>DATE : {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
                                          </div>
                                          <div class="sig_box">
                                              <p>Signature of Doctor / R.M.O</p>
                                              <p>Regn No. :</p>
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
                  <div class="d-flex gap-2 ms-auto me-3">
                      <button type="button" class="btn btn-outline-secondary" onclick="downloadDocument()">
                          <i class="bi bi-download"></i>
                          Download
                      </button>
                      <a href="{{ route('discharge.pdf', $ipd->id) }}" target="_blank" class="btn btn-primary">
                          <i class="bi bi-printer"></i> Print
                      </a>
                      {{-- <button type="button" class="btn btn-primary" onclick="openPdf({{ $ipd->id }})">
                          <i class="bi bi-printer"></i>
                          Print
                      </button> --}}
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
              console.log(data);

              if (!data) return;

              /* -------------------------
                 SIMPLE TEXT FIELDS
              ------------------------- */

              setText('discharge_type_head', data.reason_discharge);
              setText('discharge_no', data.discharge_number);
              setText('patient_name_text', data.patient_name);
              setText('patient_address_text', data.address);
              setText('admission_no_text', data.admission_no);
              setText('gender_text', data.gender);
              setText('age_text', data.age);
              setText('mobile_text', data.phone);
              setText('bed_text', data.bed);
              setText('under_care_text', data.under_care_dr);
              //   setText('registration_no', data.registration_no);
              //   setText('discharge_type_text', data.reason_discharge);

              /* -------------------------
                 DATE & TIME HANDLING
              ------------------------- */
              setText('admission_date_text', formatDate(new Date(data.admission_date)));
              setText('admission_time_text', data.admit_time);

              setText('discharge_date_text', data.discharge_date);
              setText('discharge_time_text', data.discharge_time);

              if (data.ot_date) {
                  setText('ot_date_text', data.ot_date);
              }

              /* -------------------------
                 CKEDITOR (HTML) FIELDS
              ------------------------- */

              setHTML('diagnosis_html', data.diagnosis);
              setHTML('ot_note_html', data.ot_note);
              setHTML('discharge_advice_html', data.discharge_advice);
              setHTML('present_complains_html', data.present_complaints);

              setImage('barcode', data.barcode);
          });


      function setText(id, value) {
          const el = document.getElementById(id);
          if (!el) {
              console.warn(`Element not found: ${id}`);
              return;
          }
          if (el) el.textContent = value ?? '';
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
      function openPdf(id) {
          const baseUrl = "{{ route('discharge.pdf', ['id' => 'ID']) }}";
          const finalUrl = baseUrl.replace('ID', id);
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
