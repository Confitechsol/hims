<style>
    .hospital_logo {
        width: 150px;
        height: auto;
    }
</style>
<div id="showPrescriptionModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="fw-bold modal-title">Prescription</h5>
                <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal"
                    aria-label="Close"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="card border-0">
                    <div class="card-body">
                        <!-- Items -->
                        <div class="d-flex align-items-center justify-content-between border-1 border-bottom pb-3 mb-3">
                            <div class="hospital_logo">
                                <img src={{ asset('assets/images/logo.webp') }} class="logo-white" alt="logo">
                            </div>
                            <div class="hospital_info">
                                <div class="py-1 px-2 text-end">
                                    <div class="">Address : 10/4D, Elgin Road, Kolkata - 700020</div>
                                    <div class="">Phone No : 0334060-8313</div>
                                    <div class="">Email : cognsihealth@gmail.com</div>
                                </div>

                            </div>
                        </div>

                        <!-- Items -->
                        <div
                            class="d-flex align-items-center justify-content-between border-1 border-bottom pb-3 mb-3 flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <!-- <div class="avatar avatar-xxl rounded bg-light border p-2">
                                    <img src="assets/img/icons/trust-care.svg" alt="favicon.png"
                                        class="img-fluid img1 ">
                                </div> -->
                                <div>
                                    <p class="mb-1"><strong id="doctor_name"></strong></p>
                                    <p class="mb-0" id="qualification"></p>
                                </div>
                            </div>

                            <div class="text-lg-end">
                                <p class="text-dark mb-1"> Department : <span class="text-body" id="department"> </span>
                                </p>
                                <p class="text-dark mb-1"> Prescribed on : <span class="text-body"
                                        id="opd_date"></span> </p>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-3">
                            <h6 class=" mb-2 fs-14 fw-medium"> Patient Details </h6>
                            <div class="px-3 py-2 bg-light rounded d-flex align-items-center justify-content-between">
                                <h6 class="m-0 fw-semibold fs-16" id="patient_name"></h6>
                                <div class="d-flex align-items-center gap-3">
                                    <p class="mb-0 text-dark" id="age_gender"></p>
                                    <p class="mb-0 text-dark"> <span class="text-body"> Blood </span> : <span
                                            id="blood_group"></span></p>
                                    <p class="mb-0 text-dark"> Patient ID: <span class="text-body"
                                            id="p_id"></span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-4">
                            <h6 class="mb-3 fs-16 fw-bold text-center" id="pres_type">Prescription</h6>
                            <div class="">
                                <h6 class="mb-3 fs-16 fw-bold text-center">Medicines</h6>
                                <!-- Table List -->
                                <div class="table-responsive border bg-white">
                                    <table class="table table-nowrap">
                                        <thead>
                                            <tr>
                                                <th>SNO</th>
                                                <th>Medicine Name</th>
                                                <th>Dosage</th>
                                                <th> Duration </th>
                                                <th> Timings</th>
                                            </tr>
                                        </thead>
                                        <tbody id="medicineTableBody">

                                        </tbody>
                                    </table>
                                </div>
                                <h6 class="mb-3 mt-3 fs-16 fw-bold text-center">Tests</h6>
                                <div class="table-responsive border bg-white">
                                    <table class="table table-nowrap">
                                        <thead>
                                            <tr>
                                                <th>SNO</th>
                                                <th>Pathology Test Name</th>
                                                <th>Radiology Test Name</th>

                                            </tr>
                                        </thead>
                                        <tbody id="pathRadTableBody">

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /Table List -->
                            </div>
                        </div>


                        <!-- Items -->
                        <div class="pb-3 mb-3 border-1 border-bottom">
                            <h6 class="mb-1 fs-16 fw-semibold">Advice</h6>
                            <p class="text-wrap" id="prescription_advice"></p>
                        </div>

                        <!-- Items -->

                        <div
                            class="pb-3 mb-3 border-1 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="">
                                <h6 class="mb-1 fs-16 fw-semibold"> Follow Up </h6>
                                <p id="pres_followup"></p>
                            </div>
                            <div class="">
                                <img src="../assets/img/icons/signature-img.svg" alt="" class="img-fluid ">
                                <h6 class="fs-14 fw-semibold" id="dr_sign"></h6>
                                <p class="fs-13 fw-normal" id="dr_sign_specialization"></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center w-100 d-flex align-items-center justify-content-center">
                    <a href="#" class="btn btn-md btn-dark me-2 d-flex align-items-center"> <i
                            class="fa-brands fa-whatsapp"></i> Whatsapp</a>
                    <a href="#" class="btn btn-md btn-primary me-2 d-flex align-items-center"> <i
                            class="fa-solid fa-at"></i> Email</a>
                    <a href="#" onclick="printPdf()" class="btn btn-md btn-warning d-flex align-items-center"> <i
                            class="fa-solid fa-print"></i> Print</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {

        const showPrescriptionModal = document.getElementById("showPrescriptionModal");

        showPrescriptionModal.addEventListener('show.bs.modal', function(event) {

            const button = event.relatedTarget;

            const drName = document.getElementById('doctor_name');
            const qualification = document.getElementById('qualification');
            const dAdvice = document.getElementById('prescription_advice');
            const department = document.getElementById('department');
            const followup = document.getElementById('pres_followup');
            const opdDate = document.getElementById('opd_date');
            const pName = document.getElementById('patient_name');
            const ageGender = document.getElementById('age_gender');
            const bGrp = document.getElementById('blood_group');
            const pId = document.getElementById('p_id');
            const drSign = document.getElementById('dr_sign');
            const specialization = document.getElementById('dr_sign_specialization');
            const presType = document.getElementById('pres_type');

            const isOpd = button.getAttribute('data-is-opd');

            if (isOpd === "true") {

                const opd_id = button.getAttribute('data-id');
                const pres_id = button.getAttribute('data-pres-id');
                const prescribe_date = button.getAttribute('data-prescription-date');
                /* ================= OPD DETAILS ================= */
                fetch(`{{ route('getOpdById', ['id' => 'ID']) }}`.replace('ID', opd_id))
                    .then(res => res.json())
                    .then(data => {

                        // drName.innerHTML = data.doctor?.name ?? '-';
                        // drSign.innerHTML = data.doctor?.name ?? '-';
                        specialization.innerHTML = data.doctor?.specialization ?? '-';
                        qualification.innerHTML = data.doctor?.qualification ?? '-';
                        department.innerHTML = data.doctor?.department?.department_name ?? '-';

                        opdDate.innerHTML = prescribe_date ?? '-';
                        pName.innerHTML = data.patient?.patient_name ?? '-';
                        ageGender.innerHTML =
                            (data.patient?.age ?? '-') + ' Y / ' + (data.patient?.gender ?? '-');
                        bGrp.innerHTML = data.patient?.blood_group?.name ?? '-';
                        pId.innerHTML = data.patient?.patient_id ?? '--';

                        presType.innerHTML = 'OPD Prescription';
                    });

                /* ================= OPD MEDICINES ================= */
                fetch(`{{ route('getOpdMedicineById', ['id' => 'ID']) }}`.replace('ID', pres_id))
                    .then(res => res.json())
                    .then(data => {

                        const tableBody = document.getElementById("medicineTableBody");
                        tableBody.innerHTML = "";

                        if (!data.length) {
                            tableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center">No Records Found</td>
                            </tr>`;
                            return;
                        }

                        data.forEach((item, index) => {
                            tableBody.insertAdjacentHTML("beforeend", `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.pharmacy?.medicine_name ?? '-'}</td>
                                <td>${item.medicine_dosage
                                    ? item.medicine_dosage.dosage + ' ' + (item.medicine_dosage.unit?.unit_name ?? '')
                                    : '-'}</td>
                                <td>${item.dose_duration?.name ?? '-'}</td>
                                <td>${item.dose_interval?.name ?? '-'}</td>
                            </tr>
                        `);
                        });
                    });

                /* ================= OPD PATHOLOGY + RADIOLOGY ================= */
                fetch(`{{ route('getOpdRadPathById', ['id' => 'ID']) }}`.replace('ID', pres_id))
                    .then(res => res.json())
                    .then(data => {
                        console.log('Path/Rad API:', data);

                        drName.innerHTML = data.prescription?.prescribed_by?.name ?? '-';
                        drSign.innerHTML = data.prescription?.prescribed_by?.name ?? '-';

                        console.log('Advice value:', data.prescription.advice);
                        dAdvice.innerHTML = data?.prescription?.advice ?? '-';
                        followup.innerHTML = data?.prescription?.footer_note ?? '-';
                        console.log('Full Prescription:', data.prescription);
                        console.log('Prescription doctor:', data.prescription?.prescribed_by?.name);
                        console.log('Advice value:', data.prescription.advice);
                        const tableBody = document.getElementById("pathRadTableBody");
                        tableBody.innerHTML = "";

                        const pathology = data.pathology || [];
                        const radiology = data.radiology || [];

                        const maxLength = Math.max(pathology.length, radiology.length);

                        if (maxLength === 0) {
                            tableBody.innerHTML = `
                            <tr>
                                <td colspan="3" class="text-center">No Records Found</td>
                            </tr>
                        `;
                            return;
                        }

                        for (let i = 0; i < maxLength; i++) {
                            tableBody.insertAdjacentHTML("beforeend", `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${pathology[i]?.test_name ?? '-'}</td>
                                <td>${radiology[i]?.test_name ?? '-'}</td>
                            </tr>
                        `);
                        }


                    })

                    .catch(err => console.error('Error loading tests:', err));
            }
        });
    });

    function generatePdf() {
        return new Promise((resolve) => {
            const target = document.querySelector("#showPrescriptionModal .modal-body .card");
            const clone = target.cloneNode(true);
            clone.style.position = "absolute";
            clone.style.top = "-9999px";
            clone.style.height = "auto";
            clone.style.overflow = "visible";

            document.body.appendChild(clone);

            html2canvas(clone, {
                scale: 2,
                useCORS: true
            }).then(canvas => {
                const {
                    jsPDF
                } = window.jspdf;

                const pdf = new jsPDF('p', 'mm', 'a4');

                const imgData = canvas.toDataURL('image/png');

                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = pdf.internal.pageSize.getHeight();

                const imgWidth = pdfWidth;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                // Center vertically if smaller than A4
                const y = imgHeight < pdfHeight ? (pdfHeight - imgHeight) / 2 : 0;

                pdf.addImage(imgData, 'PNG', 0, y, imgWidth, imgHeight);

                resolve(pdf);
                // document.body.removeChild(clone);
            });
        });
    }

    function printPdf() {
        Swal.fire({
            title: 'Generating PDF...',
            text: 'Please wait a moment',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        generatePdf().then(pdf => {
                Swal.close();
                const blob = pdf.output('bloburl');

                const printWindow = window.open(blob);
                printWindow.onload = () => {
                    printWindow.focus();
                    printWindow.print();
                };
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Failed to generate PDF'
                });
                console.error(error);
            });
    }
</script>
