<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">

        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Doctor Wise Appointment </h5>
                </div>

                <div class="card-body">
                    <form id="doctorwise_form" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row mb-3 align-items-center">

                            <!-- Doctor -->
                            <div class="col-md-4">
                                <label for="doctor" class="form-label">Doctor <span class="text-danger">*</span></label>
                                <select class="form-select" id="doctor" name="doctor">
                                    <option value="">Select Doctor</option>
                                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($doctor->id); ?>"><?php echo e($doctor->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Date -->
                            <div class="col-md-4">
                                <label for="date" class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="date" id="date">
                            </div>

                            <!-- Search Button -->
                            <div class="col-md-4">
                                <button type="button" id="searchBtn" class="btn btn-primary btn-sm mt-4">Search</button>
                            </div>
                        </div>
                    </form>

                    <!-- Dynamic Table -->
                    <div id="appointmentResults" class="table-responsive mt-3"></div>


                </div>
            </div>
        </div>

        
    </div>







    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for the doctor dropdown
            $('#doctor').select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: true
            });
        });
    </script>
    <script>
        document.getElementById('searchBtn').addEventListener('click', function() {
    const doctorId = document.getElementById('doctor').value;
    const date = document.getElementById('date').value;

    fetch("<?php echo e(route('appointments.search')); ?>", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
        },
        body: JSON.stringify({ doctor_id: doctorId, date: date })
    })
    .then(response => response.json())
    .then(data => {
        const appointments = data.appointments || []; // fallback if undefined
        let html = '<table class="table table-bordered"><thead><tr><th>Patient</th><th>Appointment ID</th><th>Date</th><th>Doctor</th><th>Status</th></tr></thead><tbody>';

        if(appointments.length > 0) {
            appointments.forEach(app => {
                const patientName = app.patient?.patient_name ?? 'N/A';
                const doctorName = app.doctor_user?.name ?? 'N/A';
                const appointmentDate = app.date ? new Date(app.date).toLocaleDateString() : 'N/A';
                const status = app.appointment_status ?? 'N/A';

                html += `<tr>
                            <td>${patientName}</td>
                            <td>${app.appointment_id ?? 'N/A'}</td>
                            <td>${appointmentDate}</td>
                            <td>${doctorName}</td>
                            <td>${status}</td>
                        </tr>`;
            });
        } else {
            html += '<tr><td colspan="5" class="text-center">No appointments found</td></tr>';
        }

        html += '</tbody></table>';
        document.getElementById('appointmentResults').innerHTML = html;
    })
    .catch(error => console.error('Error fetching appointments:', error));
});



    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/appointments/doctor_wise.blade.php ENDPATH**/ ?>