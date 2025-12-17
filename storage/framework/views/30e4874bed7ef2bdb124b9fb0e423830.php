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


                <div class="card">
                    <div class="card-body">
                        <!-- Items -->
                        <div class="d-flex align-items-center justify-content-between border-1 border-bottom pb-3 mb-3">
                            <div class="hospital_logo">
                                <img src=<?php echo e(asset('assets/images/logo.webp')); ?> class="logo-white" alt="logo">
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
                                        id="ipd_date"></span> </p>
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
                            <h6 class="mb-3 fs-16 fw-bold text-center" id="pres_type">Cardiology Prescription</h6>
                            <div class="">
                                <!-- Table List -->
                                <div class="table-responsive border bg-white">
                                    <table class="table table-nowrap">
                                        <thead>
                                            <tr>
                                                <th>SNO</th>
                                                <th>Medecine Name</th>
                                                <th>Dosage</th>
                                                <th> Duration </th>
                                                <th> Timings</th>
                                            </tr>
                                        </thead>
                                        <tbody id="medicineTableBody">

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /Table List -->
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="pb-3 mb-3 border-1 border-bottom">
                            <h6 class="mb-1 fs-16 fw-semibold">Advice</h6>
                            <p class="text-wrap"> An account of the present illness, which includes the circumstances
                                surrounding the
                                onset of recent health changes and the chronology of subsequent events that have led the
                                patient to seek medical care, is essential to understanding the course of the disease
                                process. Medications are listed in the medical history because they may play a role in
                                the current illness. </p>
                        </div>

                        <!-- Items -->

                        <div
                            class="pb-3 mb-3 border-1 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="">
                                <h6 class="mb-1 fs-16 fw-semibold"> Follow Up </h6>
                                <p> Follow u p after 3 months, Have to come on empty stomach</p>
                            </div>
                            <div class="">
                                <img src="../assets/img/icons/signature-img.svg" alt="" class="img-fluid ">
                                <h6 class="fs-14 fw-semibold" id="dr_sign"></h6>
                                <p class="fs-13 fw-normal" id="dr_sign_specialization"></p>
                            </div>
                        </div>

                        <div class="text-center d-flex align-items-center justify-content-center">
                            <a href="#" class="btn btn-md btn-dark me-2 d-flex align-items-center"> <i
                                    class="fa-brands fa-whatsapp"></i> Whatsapp</a>
                            <a href="#" class="btn btn-md btn-primary d-flex align-items-center"> <i
                                    class="fa-solid fa-at"></i> Email</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const showPrescriptionModal = document.getElementById("showPrescriptionModal");
        showPrescriptionModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Button that triggered the modal

            const drName = document.getElementById('doctor_name')
            const qualification = document.getElementById('qualification')
            const department = document.getElementById('department')
            const ipdDate = document.getElementById('ipd_date')
            const pName = document.getElementById('patient_name')
            const ageGender = document.getElementById('age_gender')
            const bGrp = document.getElementById('blood_group')
            const pId = document.getElementById('p_id')
            const drSign = document.getElementById('dr_sign')
            const specialization = document.getElementById('dr_sign_specialization')
            const presType = document.getElementById('pres_type')

            var isIpd = button.getAttribute('data-is-ipd');
            if (isIpd === "true") {
                const ipd_id = button.getAttribute('data-id');
                const pres_id = button.getAttribute('data-pres-id');

                const baseUrl = "<?php echo e(route('getIpdById', ['id' => 'ID'])); ?>";
                const finalUrl = baseUrl.replace('ID', ipd_id);

                fetch(finalUrl)
                    .then(response => response.json())
                    .then(data => {
                        window.nursesData = data;
                        drName.innerHTML = data.doctor.name
                        drSign.innerHTML = data.doctor.name
                        specialization.innerHTML = data.doctor.specialization
                        qualification.innerHTML = data.doctor.qualification
                        department.innerHTML = data.doctor.department.department_name
                        ipdDate.innerHTML = data.date
                        pName.innerHTML = data.patient.patient_name
                        ageGender.innerHTML = data.patient.age + ' Y/ ' + data.patient.gender
                        bGrp.innerHTML = data.patient.blood_group.name
                        pId.innerHTML = '--'
                        presType.innerHTML = 'IPD Prescription'
                        const baseUrlMed = "<?php echo e(route('getIpdMedicineById', ['id' => 'ID'])); ?>";
                        const finalUrlMed = baseUrlMed.replace('ID', pres_id);
                        fetch(finalUrlMed).then(response => response.json())
                            .then(data => {
                                window.nursesData = data;
                                const tableBody = document.getElementById("medicineTableBody");
                                tableBody.innerHTML = "";
                                data.forEach((item, index) => {
                                    if (!data || data.length === 0) {
                                        tableBody.innerHTML = `
                                            <tr>
                                                <td colspan="6" class="text-center">No Records Found</td>
                                            </tr>
                                        `;
                                        return;
                                    }
                                    const row = `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${item.pharmacy.medicine_name ?? '-'}</td>
                                            <td>${item.medicine_dosage.dosage+" "+item.medicine_dosage.unit.unit_name ?? '-'}</td>
                                            <td>${item.dose_duration.name ?? '-'}</td>
                                            <td>${item.dose_interval.name ?? '-'}</td>
                                        </tr>
                                    `;

                                    tableBody.insertAdjacentHTML("beforeend", row);
                                });
                            })
                            .catch(error => {
                                console.error('Error fetching Medicines:', error);
                                nurseSelect.innerHTML =
                                    '<option value="">Error loading options</option>';
                            });

                    })
                    .catch(error => {
                        console.error('Error fetching Prescriptions:', error);
                        nurseSelect.innerHTML = '<option value="">Error loading options</option>';
                    });

            }else{
                const opd_id = button.getAttribute('data-id');
                const pres_id = button.getAttribute('data-pres-id');

                const baseUrl = "<?php echo e(route('getOpdById', ['id' => 'ID'])); ?>";
                const finalUrl = baseUrl.replace('ID', opd_id);

                fetch(finalUrl)
                    .then(response => response.json())
                    .then(data => {
                        window.nursesData = data;
                        drName.innerHTML = data.doctor.name
                        drSign.innerHTML = data.doctor.name
                        specialization.innerHTML = data.doctor.specialization
                        qualification.innerHTML = data.doctor.qualification
                        department.innerHTML = data.doctor.department.department_name
                        ipdDate.innerHTML = data.date
                        pName.innerHTML = data.patient.patient_name
                        ageGender.innerHTML = data.patient.age + ' Y/ ' + data.patient.gender
                        bGrp.innerHTML = data.patient.blood_group.name
                        pId.innerHTML = '--'
                        presType.innerHTML = 'OPD Prescription'
                        const baseUrlMed = "<?php echo e(route('getOpdMedicineById', ['id' => 'ID'])); ?>";
                        const finalUrlMed = baseUrlMed.replace('ID', pres_id);
                        fetch(finalUrlMed).then(response => response.json())
                            .then(data => {
                                window.nursesData = data;
                                const tableBody = document.getElementById("medicineTableBody");
                                tableBody.innerHTML = "";
                                data.forEach((item, index) => {
                                    if (!data || data.length === 0) {
                                        tableBody.innerHTML = `
                                            <tr>
                                                <td colspan="6" class="text-center">No Records Found</td>
                                            </tr>
                                        `;
                                        return;
                                    }
                                    const row = `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${item.pharmacy.medicine_name ?? '-'}</td>
                                            <td>${item.medicine_dosage.dosage+" "+item.medicine_dosage.unit.unit_name ?? '-'}</td>
                                            <td>${item.dose_duration.name ?? '-'}</td>
                                            <td>${item.dose_interval.name ?? '-'}</td>
                                        </tr>
                                    `;

                                    tableBody.insertAdjacentHTML("beforeend", row);
                                });
                            })
                            .catch(error => {
                                console.error('Error fetching Medicines:', error);
                                nurseSelect.innerHTML =
                                    '<option value="">Error loading options</option>';
                            });

                    })
                    .catch(error => {
                        console.error('Error fetching Prescriptions:', error);
                        nurseSelect.innerHTML = '<option value="">Error loading options</option>';
                    });
            }
        })
    })
</script>
<?php /**PATH C:\xampp\htdocs\hims\resources\views/components/modals/show-prescription-modal.blade.php ENDPATH**/ ?>