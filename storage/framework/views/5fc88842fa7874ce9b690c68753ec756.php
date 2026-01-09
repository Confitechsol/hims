<?php $__env->startSection('content'); ?>
    <!-- ========================
        Start Page Content
    ========================= -->

    

        <style>
            .modal-backdrop.show:nth-of-type(2) {
                z-index: 1060;
                /* higher backdrop for nested modal */
            }

            #new_patient {
                z-index: 1070;
                /* ensure new modal is above the first */
            }
        </style>

        <!-- Start Content -->
        <div class="content pb-0">


            <!-- row start -->
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                                <div>
                                    <h4 class="fw-bold mb-0">Appointment Details</h4>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <div class="text-end d-flex">
                                        <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                            data-bs-toggle="modal" data-bs-target="#add_appointment"><i
                                                class="ti ti-plus me-1"></i>Add
                                            Appointment</a>
                                    </div>
                                    <!-- First Modal -->
                                    <div class="modal fade" id="add_appointment" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content">
                                                <form method="POST" action="<?php echo e(route('appointments.store')); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <div class="row w-100 align-items-center">
                                                            <div class="col-md-7">
                                                                <select class="form-select select2" id="patient_id" name="patient_id"
                                                                    data-placeholder="Enter Patient Name or Id…" required>
                                                                    <option value="">Select Patient</option>
                                                                    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($patient->id); ?>" >
                                                                            <?php echo e($patient->patient_name); ?>

                                                                        </option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                                                                    data-bs-target="#add_patient">
                                                                    <i class="ti ti-plus me-1"></i>New Patient
                                                                </a>
                                                            </div>
                                                            <div class="col-md-1 text-end">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row align-items-center gy-3">
                                                            <div class="col-md-3">
                                                                <label for="doctor" class="form-label">Doctor <span class="text-danger">*</span></label>
                                                                <select class="form-select js-example-basic-single" id="doctor" name="doctor" required>
                                                                    <option value="">Select Doctor</option>
                                                                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($doctor->id); ?>" >
                                                                            <?php echo e($doctor->name); ?>

                                                                        </option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="doctor_fees" class="form-label">Doctor Fees (INR)</label>
                                                                <input type="text" name="doctor_fees" id="doctor_fees" class="form-control">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="shift" class="form-label">Shift <span class="text-danger">*</span></label>
                                                                <select class="form-select" id="shift" name="shift" required>
                                                                    <option value="">Select Shift</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="datetimepicker" class="form-label">Appointment Date</label>
                                                                <input type="date" id="datetimepicker" name="appointment_date" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">Patient Type</label>
                                                                <select class="form-select" name="case_type">
                                                                    <option value="">Select Case Type</option>
                                                                    <option value="Old Patient">Old Patient</option>
                                                                    <option value="New Patient">New Patient</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="slot" class="form-label fw-bold">Slot</label>
                                                                <select id="slot" name="slot" class="form-select">
                                                                    <option value="">Select</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="appointment_priority" class="form-label">Appointment Priority</label>
                                                                <select class="form-select" id="appointment_priority" name="appointment_priority">
                                                                    <option value="">Select Priority</option>
                                                                        <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($priority->id); ?>" >
                                                                                <?php echo e($priority->appoint_priority); ?>

                                                                            </option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                                
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="payment_method" class="form-label">Payment Method</label>
                                                                <select class="form-select" id="payment_method" name="payment_method">
                                                                    <option value="">Select</option>
                                                                    <option value="cash">Cash</option>
                                                                    <option value="card">Card</option>
                                                                    <option value="upi">UPI</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="status" class="form-label">Status</label>
                                                                <select class="form-select" id="status" name="status">
                                                                    <option value="pending">Pending</option>
                                                                    <option value="confirmed">Confirmed</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="discount_percentage" class="form-label">Discount Percentage</label>
                                                                <input type="text" id="discount_percentage" name="discount_percentage" class="form-control">
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="message" class="form-label">Message</label>
                                                                <textarea name="message" id="message" class="form-control"></textarea>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <label for="live_con" class="form-label">Live Consultant (On Video Conference)</label>
                                                                <select class="form-select" id="live_con" name="live_con">
                                                                    <option value="">Select</option>
                                                                    <option value="no">No</option>
                                                                    <option value="yes">Yes</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save & Print</button>
                                                        <button type="submit" class="btn btn-secondary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                   


                                    
                                    <a href="<?php echo e(route('appointments.doctor-wise')); ?>"
                                        class="btn btn-outline-primary d-inline-flex align-items-center"><i
                                            class="ti ti-menu me-1"></i>Doctor Wise</a>
                                    <!-- <a href="<?php echo e(route('appointments.queue')); ?>"
                                        class="btn btn-outline-primary d-inline-flex align-items-center"><i
                                            class="ti ti-menu me-1"></i>Queue</a> -->
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Table start -->
                            <div class="table-responsive table-nowrap">
                                <table class="table border">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Patient Name</th>
                                            <th>Appointment No</th>
                                            <th>Appointment Date</th>
                                            <th>Phone</th>
                                            <th>Gender</th>
                                            <th>Doctor</th>
                                            <th>Source</th>
                                            <th>Priority</th>
                                            <th>Live Consultant</th>
                                            <th>Discount</th>
                                            <th>Fees(INR)</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <h6 class="fs-14 mb-1">
                                                     <a href="<?php echo e(route('patient.view', $appointment->patient_id)); ?>" 
                                                        class="fw-semibold text-primary">
                                                            <?php echo e($appointment->patient->patient_name ?? 'N/A'); ?> (<?php echo e($appointment->patient_id); ?>)
                                                    </a>
                                                </h6>
                                            </td>

                                            <td><?php echo e($appointment->appointment_id ?? 'N/A'); ?></td>

                                            <td>
                                                <?php echo e(\Carbon\Carbon::parse($appointment->date)->format('d/m/Y')); ?>

                                                <!-- <?php echo e($appointment->slot->start_time ?? ''); ?> -->
                                            </td>

                                            <td><?php echo e($appointment->patient->contact_no ?? '-'); ?></td>

                                            <td><?php echo e(ucfirst($appointment->patient->gender ?? 'N/A')); ?></td>

                                            <td><?php echo e($appointment->doctorUser->name ?? 'N/A'); ?> (<?php echo e($appointment->doctorUser->doctor_id ?? ''); ?>)</td>

                                            <td><?php echo e(ucfirst($appointment->source ?? 'Offline')); ?></td>

                                            <td><?php echo e($appointment->priority); ?></td>

                                            <td><?php echo e(ucfirst($appointment->live_consult ?? 'No')); ?></td>

                                            <td><?php echo e(number_format($appointment->discount_percentage ?? 0, 2)); ?></td>

                                            <td><?php echo e(number_format($appointment->amount ?? 0, 2)); ?></td>

                                            <td>
                                                <?php if($appointment->appointment_status === 'confirmed'): ?>
                                                    <span class="badge fs-13 py-1 badge-soft-success border border-success rounded text-success fw-medium">Confirmed</span>
                                                <?php elseif($appointment->appointment_status === 'pending'): ?>
                                                    <span class="badge fs-13 py-1 badge-soft-warning border border-warning rounded text-warning fw-medium">Pending</span>
                                                <?php elseif($appointment->appointment_status === 'rescheduled'): ?>
                                                    <span class="badge fs-13 py-1 badge-soft-secondary border border-secondary rounded text-secondary fw-medium">Rescheduled</span>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <div class="d-flex">
                                                    <a href="#"
                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                    data-bs-toggle="tooltip" title="Show">
                                                        <i class="ti ti-menu"></i>
                                                    </a>

                                                    <a href="#"
                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-warning rounded-pill"
                                                    data-bs-toggle="tooltip" title="Print">
                                                        <i class="ti ti-file-description"></i>
                                                    </a>

                                                    <!-- <a href="javascript:void(0);"
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill reschedule-btn"
                                                data-bs-toggle="tooltip" 
                                                title="Reschedule"
                                                data-id="<?php echo e($appointment->id); ?>"
                                                data-patient="<?php echo e($appointment->patient->patient_name ?? ''); ?>"
                                                data-patientid="<?php echo e($appointment->patient_id); ?>"
                                                data-doctor="<?php echo e($appointment->doctor->name ?? ''); ?>"
                                                data-doctorid="<?php echo e($appointment->doctor_id); ?>"
                                                data-fees="<?php echo e($appointment->doctor_fees ?? ''); ?>"
                                                data-date="<?php echo e($appointment->appointment_date); ?>"
                                                data-shift="<?php echo e($appointment->shift_id); ?>"
                                                data-slot="<?php echo e($appointment->slot_id); ?>"
                                                data-priority="<?php echo e($appointment->appointment_priority); ?>"
                                                data-status="<?php echo e($appointment->status); ?>"
                                                data-payment="<?php echo e($appointment->payment_method); ?>"
                                                data-discount="<?php echo e($appointment->discount_percentage); ?>"
                                                data-message="<?php echo e($appointment->message); ?>"
                                                data-live="<?php echo e($appointment->live_con); ?>">
                                                    <i class="ti ti-calendar-time"></i>
                                                </a> -->

                                                <a href="javascript:void(0);" 
                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill rescheduleBtn" 
                                                    data-id="<?php echo e($appointment->id); ?>" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Reschedule">
                                                        <i class="ti ti-calendar-time"></i>
                                                </a>


                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="13" class="text-center text-muted">No appointments found.</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table end -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>

        <?php echo $__env->make('components.modals.add-patients-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
         <!-- Edit Modal (nested) -->
        <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <form id="rescheduleForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                            <h5 class="mb-0 text-dark fw-bold">Reschedule Appointment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row gy-3">
                                
                                <div class="col-md-3">
                                    <label>Patient</label>
                                    <input type="text" id="reschedule_patient" class="form-control" readonly>
                                </div>

                                
                                <div class="col-md-3">
                                    <label>Doctor</label>
                                    <input type="text" id="reschedule_doctor" class="form-control" readonly>
                                </div>

                                
                                <div class="col-md-3">
                                    <label>Doctor Fees (INR)</label>
                                    <input type="text" id="reschedule_fees" class="form-control" readonly>
                                </div>

                                
                                <div class="col-md-3">
                                    <label>Shift</label>
                                    <select id="reschedule_shift" name="shift" class="form-select" required></select>
                                </div>

                                
                                <div class="col-md-3">
                                    <label>Date</label>
                                    <input type="date" id="reschedule_date" name="appointment_date" class="form-control" required>
                                </div>

                                
                                <div class="col-md-3">
                                    <label>Slot</label>
                                    <select id="reschedule_slot" name="slot" class="form-select" required></select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label>Status</label>
                                    <select id="reschedule_status" name="status" class="form-select">
                                        <option value="pending">Pending</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="rescheduled">Rescheduled</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>
        <script>

            document.querySelectorAll('.rescheduleBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;

                    fetch(`<?php echo e(url('appointment-details/appointments')); ?>/${id}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            const appointment = data.appointment;

                            // Set form action dynamically
                            const form = document.getElementById('rescheduleForm');
                            form.action = `<?php echo e(url('appointment-details/appointments')); ?>/${appointment.id}`;

                            // Fill modal fields
                            document.getElementById('reschedule_patient').value = appointment.patient.patient_name;
                            document.getElementById('reschedule_doctor').value = appointment.doctor_user.name;
                            document.getElementById('reschedule_fees').value = appointment.amount;
                            

                            // Set date (extract yyyy-mm-dd)
                            document.getElementById('reschedule_date').value = appointment.date.split('T')[0];

                            // Populate shifts
                            const shiftSelect = document.getElementById('reschedule_shift');
                            shiftSelect.innerHTML = '';
                            data.shifts.forEach(shift => {
                                const option = document.createElement('option');
                                option.value = shift.id;
                                option.text = shift.global_shift?.name || 'N/A';
                                if (appointment.doctor_global_shift_id == shift.global_shift_id) {
                                    option.selected = true;
                                }
                                shiftSelect.appendChild(option);
                            });

                            // Populate slots
                            const slotSelect = document.getElementById('reschedule_slot');
                            slotSelect.innerHTML = '';
                            data.slots.forEach(slot => {
                                const option = document.createElement('option');
                                option.value = slot.id;
                                option.text = `${slot.start_time} - ${slot.end_time}`;
                                if (appointment.doctor_shift_time_id == slot.id) {
                                    option.selected = true;
                                }
                                slotSelect.appendChild(option);
                            });
                            // Populate status
                            const statusSelect = document.getElementById('reschedule_status');
                            statusSelect.value = appointment.appointment_status;

                            // Show modal
                            const rescheduleModal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
                            rescheduleModal.show();
                        })
                        .catch(error => {
                            console.error('Error fetching appointment:', error);
                        });
                });
            });

        </script>


        <script>
            $(document).ready(function () {
                // Re-initialize Select2 every time the modal is shown
                $('#add_appointment').on('shown.bs.modal', function () {
                    $('#appointment-type').select2({
                        width: '100%',
                        placeholder: 'Enter Patient Name or Id…',
                        allowClear: true,
                        dropdownParent: $('#add_appointment')

                    });
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                // Re-initialize Select2 every time the modal is shown
                $('#add_appointment').on('shown.bs.modal', function () {
                    $('#doctor').select2({
                        width: '100%',
                        placeholder: 'Select',
                        allowClear: true,
                        dropdownParent: $('#add_appointment')

                    });
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                // Re-initialize Select2 every time the modal is shown
                $('#add_appointment').on('shown.bs.modal', function () {
                    $('#shift').select2({
                        width: '100%',
                        placeholder: 'Select',
                        allowClear: true,
                        dropdownParent: $('#add_appointment')

                    });
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                // Re-initialize Select2 every time the modal is shown
                $('#add_appointment').on('shown.bs.modal', function () {
                    $('#appointment_priority').select2({
                        width: '100%',
                        placeholder: 'Select',
                        allowClear: true,
                        dropdownParent: $('#add_appointment')

                    });
                });
            });
        </script>
        
        <script>
            $(document).ready(function () {

                // Fetch shifts based on doctor
                $('#doctor').change(function() {
                    let doctorId = $(this).val();

                    if (!doctorId) {
                        $('#shift').html('<option value="">Select</option>');
                        $('#slot').html('<option value="">Select</option>');
                        return;
                    }

                    $.ajax({
                        url: '<?php echo e(route("doctor.shifts", ":doctorId")); ?>'.replace(':doctorId', doctorId),
                        type: 'GET',
                        success: function(response) {
                            let options = '<option value="">Select Shift</option>';
                            response.shifts.forEach(function(shift) {
                                options += `<option value="${shift.id}">${shift.name}</option>`;
                            });
                            $('#shift').html(options);
                        },
                        error: function() {
                            alert('Could not fetch shifts!');
                        }
                    });
                });

                // Fetch slots based on doctor + shift
                $('#shift').change(function() {
                    let doctorId = $('#doctor').val();
                    console.log('doctor:', doctorId);
                    let shiftId = $(this).val();
                    console.log('doctor:', shiftId);
                    if (!shiftId || !doctorId) {
                        $('#slot').html('<option value="">Select</option>');
                        return;
                    }

                    $.ajax({
                        url: '<?php echo e(route("doctor.slots", [":doctorId", ":shiftId"])); ?>'
                            .replace(':doctorId', doctorId)
                            .replace(':shiftId', shiftId),
                        type: 'GET',
                        success: function(response) {
                            console.log('Slots Response:', response);
                            let options = '<option value="">Select Slot</option>';
                            response.slots.forEach(function(slot) {
                                options += `<option value="${slot.id}">${slot.day} (${slot.start_time} - ${slot.end_time})</option>`;
                            });
                            $('#slot').html(options);
                        },
                        error: function(xhr) {

                            console.error('Error:', xhr.responseText);
                            alert('Could not fetch slots!');
                        }
                    });
                });

                $('.js-example-basic-single').select2();
            });
        </script>
        <script>
$(document).ready(function() {

     $('#doctor').change(function () {
        alert('hi');
        // When doctor is selected, get default charge_category_id = 1
        $.ajax({
            url: '<?php echo e(route("appointments.getDoctorFees")); ?>',
            type: 'GET',
            success: function (response) {
                if (response.fees) {
                    $('#doctor_fees').val(response.fees);
                } else {
                    $('#doctor_fees').val('');
                }
            },
            error: function () {
                $('#doctor_fees').val('');
            }
        });
    });
    // Fetch priorities dynamically when modal is opened
    
});
</script>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/appointments/appointment_details.blade.php ENDPATH**/ ?>