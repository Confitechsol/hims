@extends('layouts.adminLayout')

@section('select2cdn')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
@endsection

<style>
    /* KEEPING YOUR EXACT STYLES */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    }

    .card-title {
        font-weight: 600;
        font-size: 1.35rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white !important;
    }

    .card-title i {
        font-size: 1.5rem;
        color: white !important;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #ab00db66;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #ab00db66;
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .med-row {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .btn-add-medicine {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        color: #fff;
        border: none;
        padding: .625rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .ck-editor__editable {
        min-height: 250px;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        padding: 1rem 1rem 0;
        background: white;
        justify-content: flex-end;
    }
</style>

@section('content')
    <div class="container-fluid mt-4">

        <div class="card shadow-lg border-0">

            <div class="card-header bg-primary">
                <h5 class="card-title" class="mb-0">
                    <i class="bi bi-clipboard-check"></i>
                    Edit Patient Discharge
                </h5>
            </div>
            <div class="card-body">
                <div id="medLoader"
                    class="position-absolute top-0 start-0 w-100 h-100 align-items-center justify-content-center bg-white bg-opacity-75"
                    style="z-index: 1056; display: none;">
                    <div class="text-center position-absolute" style="top: 20%;">
                        <div class="spinner-border text-primary mb-2" role="status"></div>
                        <div class="fw-semibold">Loading Form data...</div>
                    </div>
                </div>

                <form id="patientDischargeUpdateForm" method="POST"
                    action="{{ route('discharge.update', $dischargeData->id) }}">

                    @csrf
                    @method('PUT')

                    <input type="hidden" name="ipd_details_id" value="{{ $dischargeData->ipd_details_id }}">
                    <input type="hidden" name="is_draft" value="{{ $dischargeData->is_draft == 1 ? 'true' : 'false' }}"
                        id="is-Draft">

                    <!-- BASIC INFORMATION -->

                    <h5 class="section-title">
                        <i class="bi bi-person-badge"></i>
                        Basic Information
                    </h5>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Patient Name</label>
                            <input type="text" class="form-control" name="patient_name"
                                value="{{ $dischargeData->patient_name }}" autocomplete="off" readonly>
                            <input type="hidden" class="form-control" name="patient_id"
                                value="{{ $dischargeData->patient_id }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Admission No</label>
                            <input type="text" class="form-control" name="admission_no"
                                value="{{ $dischargeData->admission_no }}" autocomplete="off" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Discharge Contact</label>
                            <input type="text" class="form-control" name="discharge_contact"
                                value="{{ $dischargeData->discharge_contact }}" autocomplete="off">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Bed</label>
                            <input type="text" class="form-control" name="bed" value="{{ $dischargeData->bed }}"
                                autocomplete="off">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Discharge Date</label>
                            <input type="date" class="form-control" name="discharge_date"
                                value="{{ $dischargeData->discharge_date }}" autocomplete="off">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Discharge Time</label>
                            <input type="time" class="form-control" name="discharge_time"
                                value="{{ $dischargeData->discharge_time }}" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Admit Time</label>
                            <input type="time" class="form-control" name="admit_time"
                                value="{{ $dischargeData->admit_time }}" autocomplete="off">
                        </div>

                    </div>

                    <hr class="my-4">

                    <h5 class="section-title mt-4">
                        <i class="bi bi-person-lines-fill"></i>
                        Patient Details
                    </h5>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <label for="age" class="form-label">
                                <i class="bi bi-calendar3"></i>
                                Age
                            </label>
                            <input type="text" class="form-control" id="age_text" name="age" step="0.01"
                                value="{{ $dischargeData->age }}" autocomplete="off" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="gender" class="form-label">
                                <i class="bi bi-gender-ambiguous"></i>
                                Gender
                            </label>
                            <select class="form-select" id="gender_text" name="gender">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="phone" class="form-label">
                                <i class="bi bi-telephone"></i>
                                Phone
                            </label>
                            <input type="tel" value="{{ $dischargeData->phone }}" class="form-control"
                                id="phone_text" name="phone" autocomplete="off" readonly>
                        </div>

                        <div class="col-md-3">
                            <label for="marital_status" class="form-label">
                                <i class="bi bi-heart"></i>
                                Marital Status
                            </label>
                            <select class="form-select" id="marital_status_text" name="marital_status" readonly>
                                <option value="">Select</option>
                                <option value="Married">Married</option>
                                <option value="Single">Single</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label for="address" class="form-label">
                                <i class="bi bi-geo-alt"></i>
                                Address
                            </label>
                            <textarea class="form-control" id="address_text" name="address" rows="3">{{ $dischargeData->address }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="admission_date" class="form-label">
                                <i class="bi bi-calendar-check"></i>
                                Admission Date
                            </label>
                            <input type="date" value="{{ $dischargeData->admission_date }}" class="form-control"
                                id="admission_date_text" name="admission_date" autocomplete="off">
                        </div>

                        <div class="col-md-4">
                            <label for="guardian" class="form-label">
                                <i class="bi bi-person-check"></i>
                                W/O S/O D/O
                            </label>
                            <input type="text" value="{{ $dischargeData->guardian }}" class="form-control"
                                id="guardian_text" name="guardian" autocomplete="off">
                        </div>

                        <div class="col-md-4">
                            <label for="relation" class="form-label">
                                <i class="bi bi-people"></i>
                                Relation
                            </label>
                            <input type="text" value="{{ $dischargeData->relation }}" class="form-control"
                                id="relation_text" name="relation" autocomplete="off">
                        </div>

                        <div class="col-md-4">
                            <label for="nationality" class="form-label">
                                <i class="bi bi-flag"></i>
                                Nationality
                            </label>
                            <input type="text" value="{{ $dischargeData->nationality }}" class="form-control"
                                id="nationality_text" name="nationality" autocomplete="off">
                        </div>

                        <div class="col-md-6">
                            <label for="under_care_dr" class="form-label">
                                <i class="bi bi-person-badge"></i>
                                Under Care Dr
                            </label>
                            <input type="text" value="{{ $dischargeData->under_care_dr }}" class="form-control"
                                id="under_care_dr_text" name="under_care_dr" autocomplete="off" readonly>
                            <input type="hidden" class="form-control" value="{{ $dischargeData->registration_no }}"
                                id="registration_no_text" name="registration_no">
                        </div>

                        <div class="col-md-6">
                            <label for="referral" class="form-label">
                                <i class="bi bi-arrow-right-circle"></i>
                                Referral
                            </label>
                            <input type="text" value="{{ $dischargeData->referral }}" class="form-control"
                                id="referral_text" name="referral" autocomplete="off">
                        </div>

                        <div class="col-md-12">
                            <label for="corporate" class="form-label">
                                <i class="bi bi-building"></i>
                                Corporate
                            </label>
                            <input type="text" value="{{ $dischargeData->corporate }}" class="form-control"
                                id="corporate_text" name="corporate" autocomplete="off">
                        </div>
                    </div>
                    <hr class="my-4">

                    <!-- Medical Information Section -->

                    <h5 class="section-title mt-4">
                        <i class="bi bi-heart-pulse"></i>
                        Medical Information
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="reason_discharge" class="form-label">
                                <i class="bi bi-clipboard-check"></i>
                                Discharge Type
                            </label>
                            <select class="form-select" id="reason_discharge_text" name="reason_discharge" required>
                                <option value="">Select</option>
                                <option value="DORB">DORB</option>
                                <option value="Transfer To Higher Setup">Transfer To Higher Setup</option>
                                <option value="Discharge On Request">Discharge On Request</option>
                                <option value="Doctor Refer">Doctor Refer</option>
                                <option value="Normal Discharge">Normal Discharge</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="ot_date" class="form-label">
                                <i class="bi bi-calendar-plus"></i>
                                OT Date
                            </label>
                            <input type="date" value="{{ $dischargeData->ot_date }}" class="form-control"
                                id="ot_date_text" name="ot_date" autocomplete="off">
                        </div>

                        <div class="col-md-4">
                            <label for="ot_type" class="form-label">
                                <i class="bi bi-activity"></i>
                                O.T Type
                            </label>
                            <input type="text" value="{{ $dischargeData->ot_type }}" class="form-control"
                                id="ot_type_text" name="ot_type" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label for="ot_name" class="form-label">
                                <i class="bi bi-activity"></i>
                                O.T Name
                            </label>
                            <input type="text" value="{{ $dischargeData->ot_name }}" class="form-control"
                                id="ot_name_text" name="ot_name" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <label for="ot_done" class="form-label">
                                <i class="bi bi-activity"></i>
                                O.T Count
                            </label>
                            <input type="number" value="{{ $dischargeData->ot_done }}" class="form-control"
                                id="ot_done_text" name="ot_done" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="ot_done_by">
                                <i class="bi bi-person-badge-fill"></i>
                                OT Done By
                            </label>
                            <select multiple name="ot_done_by[]" id="ot_done_by_text" class="form-select p-0">
                                <option>select</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="remarks" class="form-label">
                                <i class="bi bi-chat-left-text"></i>
                                Remarks
                            </label>
                            <textarea class="form-control" id="remarks_text" name="remarks" rows="6">{!! $dischargeData->remarks !!}</textarea>
                        </div>
                    </div>
                    <hr class="my-4">

                    <!-- MEDICINES -->

                    <h5 class="section-title">
                        <i class="bi bi-capsule"></i>
                        Discharge Medications Advice
                    </h5>

                    <div class="text-end">
                        <div id="medContainer" class="text-start">

                            @foreach ($dischargeData->meds as $i => $med)
                                <div class="med-row" data-index="{{ $i }}">

                                    <div class="medicine-row-header d-flex justify-content-between align-items-center">
                                        <span class="medicine-row-number">
                                            <i class="bi bi-capsule-pill"></i> Medicine <span
                                                class="badge bg-primary">{{ $i + 1 }}</span>
                                        </span>
                                        <button type="button"
                                            class="btn btn-danger btn-sm mt-2 remove-med btn-remove-medicine">
                                            <i class="bi bi-trash remove-med"></i>
                                        </button>
                                    </div>
                                    <div class="row g-3">

                                        <div class="col-md-2">
                                            <label class="form-label">Medicine</label>

                                            <select class="med-medicine" name="meds[]">
                                                <option value="{{ trim($med) ?? '' }}" selected>{{ trim($med) ?? '' }}
                                                </option>
                                            </select>

                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Type</label>

                                            <select class="med-types" name="med_types[]"
                                                data-selected="{{ trim($dischargeData->med_types[$i] ?? '') }}">
                                                <option value="">Select Medicine Type</option>
                                                <option value="Tablet">Tablet</option>
                                                <option value="Capsule">Capsule</option>
                                                <option value="Syrup">Syrup</option>
                                                <option value="Injection">Injection</option>
                                                <option value="Ointment">Ointment</option>
                                                <option value="Powder">Powder</option>
                                                <option value="Drop">Drop</option>
                                                {{-- <option value="{{ trim($dischargeData->med_types[$i]) }}" selected>
                                                    {{ trim($dischargeData->med_types[$i]) }}
                                                </option> --}}
                                            </select>

                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Interval</label>

                                            <select class="med-interval" name="med_interval[]">
                                                <option value="{{ trim($dischargeData->med_interval[$i]) }}" selected>
                                                    {{ trim($dischargeData->med_interval[$i]) }}
                                                </option>
                                            </select>

                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Duration</label>

                                            <select class="med-duration" name="med_duration[]">
                                                <option value="{{ trim($dischargeData->med_duration[$i]) }}" selected>
                                                    {{ trim($dischargeData->med_duration[$i]) }}
                                                </option>
                                            </select>

                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Date</label>
                                            <input type="date" class="form-control med-date py-2" name="med_date[]"
                                                value="{{ trim($dischargeData->med_dates[$i]) }}" />
                                        </div>

                                    </div>


                                </div>
                            @endforeach

                        </div>

                        <button type="button" class="btn-add-medicine mt-3" id="addMedBtn">
                            Add Medicine
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="remarks" class="form-label">
                                <i class="bi bi-chat-left-text"></i>
                                Doctor Advice
                            </label>
                            <textarea class="form-control" id="doctor_advice_text" name="doctor_advice" rows="3">{!! $dischargeData->doctor_advice !!}</textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- DIAGNOSIS -->

                    <h5 class="section-title">
                        <i class="bi bi-file-medical"></i>
                        Diagnosis & Complaints
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="present_complaints" class="form-label">
                                <i class="bi bi-clipboard-data"></i>
                                Present Complaints (Reason for Admission)
                            </label>
                            <textarea class="form-control" id="present_complaints_text" name="present_complaints" rows="4">
                                {{ $dischargeData->present_complaints }}
                            </textarea>
                        </div>

                        <div class="col-md-12">
                            <label for="diagnosis" class="form-label">
                                <i class="bi bi-clipboard2-pulse"></i>
                                Diagnosis
                            </label>
                            <textarea class="form-control" id="diagnosis_text" name="diagnosis" rows="6">
                                {!! $dischargeData->diagnosis !!}
                            </textarea>
                        </div>

                        <div class="col-md-12">
                            <label for="ot_note" class="form-label">
                                <i class="bi bi-clipboard-data"></i>
                                Treatment Done / OT Note
                            </label>
                            <textarea class="form-control" id="ot_note_text" name="ot_note" rows="4">
                                {!! $dischargeData->ot_note !!}
                            </textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="course_in_hospital" class="form-label">
                                <i class="bi bi-clipboard-data"></i>
                                Course in hospital
                            </label>
                            <textarea class="form-control" id="course_in_hospital_text" name="course_in_hospital" rows="4">
                                {!! $dischargeData->course_in_hospital !!}
                            </textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="discharge_advice" class="form-label">
                                <i class="bi bi-clipboard-data"></i>
                                Conditional at Discharge
                            </label>
                            <textarea class="form-control" id="discharge_advice_text" name="discharge_advice" rows="4">
                                {!! $dischargeData->discharge_advice !!}
                            </textarea>
                        </div>

                        <div class="col-md-12">
                            <label for="investigation_text" class="form-label">
                                <i class="bi bi-clipboard-data"></i>
                                Investigation
                            </label>
                            <textarea class="form-control" id="investigation_text" name="investigation" rows="4">
                                {!! $dischargeData->investigation !!}
                            </textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="urgent_care_text" class="form-label">
                                <i class="bi bi-clipboard-data"></i>
                                Urgent Care Instructions
                            </label>
                            <textarea class="form-control" id="urgent_care_text" name="urgent_care" rows="4">
                                {!! $dischargeData->urgent_care !!}
                            </textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="diet_advice_text" class="form-label">
                                <i class="bi bi-clipboard-data"></i>
                                Diet Advice
                            </label>
                            <textarea class="form-control" id="diet_advice_text" name="diet_advice" rows="4">
                                {!! $dischargeData->diet_advice !!}
                            </textarea>
                        </div>
                    </div>
                    <hr class="my-4">

                    @if ($dischargeData->is_draft == 1)
                        <div class="action-buttons d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-outline-secondary">
                                Update Draft
                            </button>

                            <button type="submit" class="btn btn-primary" id="final-submit">
                                Final Discharge
                            </button>
                        </div>
                    @else
                        <div class="text-end w-100">
                            <button type="submit" class="btn btn-primary">
                                Update Discharge
                            </button>
                        </div>
                    @endif

                </form>

            </div>
        </div>
    </div>

    <script>
        const genderSelect = document.querySelector('#gender_text');
        const maritalStatusSelect = document.querySelector('#marital_status_text');
        const dischargeReasonSelect = document.querySelector('#reason_discharge_text');

        const doctorsList = @json($doctors);
        const selectedDoctors = @json($dischargeData->ot_done_by ?? []);

        let medMaster = []
        let doseIntervals = []
        let doseDurations = []

        function showMedLoader() {
            document.getElementById('medLoader').style.display = 'flex';
            document.getElementById('addMedBtn').disabled = true;
        }

        function hideMedLoader() {
            document.getElementById('medLoader').style.display = 'none';
            document.getElementById('addMedBtn').disabled = false;
        }

        // gender select dynamic
        genderSelect.value = @json($dischargeData->gender) ?
            @json($dischargeData->gender) :
            '';
        genderSelect.dispatchEvent(new Event('change'));

        // marital status select dynamic
        maritalStatusSelect.value = @json($dischargeData->marital_status) ?
            @json($dischargeData->marital_status) :
            '';
        maritalStatusSelect.dispatchEvent(new Event('change'));

        // Reason select dynamic
        dischargeReasonSelect.value = @json($dischargeData->reason_discharge) ?
            @json($dischargeData->reason_discharge) :
            '';
        dischargeReasonSelect.dispatchEvent(new Event('change'));

        new TomSelect("#ot_done_by_text", {
            options: doctorsList.map(doc => ({
                value: `${doc.name}`,
                label: `${doc.name}`
            })),
            valueField: "value",
            labelField: "label",
            searchField: "label",
            create: false,
            persist: false,
            placeholder: "Select doctors",
            items: selectedDoctors // preselected values
        });

        async function loadMasters() {
            showMedLoader()
            try {
                medMaster = await fetch("{{ route('med.master') }}").then(r => r.json())
                doseIntervals = await fetch("{{ route('getDoseIntervals') }}").then(r => r.json())
                doseDurations = await fetch("{{ route('getDoseDurations') }}").then(r => r.json())

            } catch (error) {
                hideMedLoader();
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to load data',
                    text: 'Please try again',
                });
            } finally {
                hideMedLoader();
            }

        }

        function initTomSelects(row) {

            const medSelect = row.querySelector('.med-medicine');
            const medTypeSelect = row.querySelector('.med-types');
            const intervalSelect = row.querySelector('.med-interval');
            const durationSelect = row.querySelector('.med-duration');

            const medValue = medSelect.value;
            const selectedType = medTypeSelect.dataset.selected || medTypeSelect.value;

            const intervalValue = intervalSelect.value;
            const durationValue = durationSelect.value;

            const medTS = new TomSelect(medSelect, {
                options: medMaster.map(i => ({
                    value: i.name,
                    label: i.name
                })),
                valueField: 'value',
                labelField: 'label',
                searchField: 'label',
                placeholder: 'Select Medicine',
                onChange: function(value) {

                    const selectedMed = medMaster.find(m => m.name === value);

                    if (selectedMed && selectedMed.medicine_type) {

                        const formattedType =
                            selectedMed.medicine_type.charAt(0).toUpperCase() +
                            selectedMed.medicine_type.slice(1).toLowerCase();

                        const medTypeTS = medTypeSelect.tomselect;

                        if (medTypeTS) {
                            medTypeTS.setValue(formattedType);
                        }

                    } else {

                        const medTypeTS = medTypeSelect.tomselect;

                        if (medTypeTS) {
                            medTypeTS.clear();
                        }

                    }
                }
            });

            if (medValue) medTS.setValue(medValue);

            const typeTS = new TomSelect(medTypeSelect);

            if (selectedType) {
                typeTS.setValue(selectedType);
            }


            const intervalTS = new TomSelect(intervalSelect, {
                options: doseIntervals.map(i => ({
                    value: i.name,
                    label: i.name
                })),
                valueField: 'value',
                labelField: 'label',
                searchField: 'label',
                placeholder: 'Select Interval'
            });

            if (intervalValue) intervalTS.setValue(intervalValue);

            const durationTS = new TomSelect(durationSelect, {
                options: doseDurations.map(i => ({
                    value: i.name,
                    label: i.name
                })),
                valueField: 'value',
                labelField: 'label',
                searchField: 'label',
                placeholder: 'Select Duration'
            });

            if (durationValue) durationTS.setValue(durationValue);
        }
        // function initTomSelects(row) {

        //     new TomSelect(row.querySelector('.med-medicine'), {
        //         options: medMaster.map(i => ({
        //             value: i.name,
        //             label: i.name
        //         }))
        //     })

        //     new TomSelect(row.querySelector('.med-interval'), {
        //         options: doseIntervals.map(i => ({
        //             value: i.name,
        //             label: i.name
        //         }))
        //     })

        //     new TomSelect(row.querySelector('.med-duration'), {
        //         options: doseDurations.map(i => ({
        //             value: i.name,
        //             label: i.name
        //         }))
        //     })

        // }

        document.addEventListener('DOMContentLoaded', async () => {

            await loadMasters()

            document.querySelectorAll('.med-row').forEach(row => {
                initTomSelects(row)
            })

            ClassicEditor.create(document.querySelector('#remarks_text'))
            ClassicEditor.create(document.querySelector('#doctor_advice_text'))
            ClassicEditor.create(document.querySelector('#diagnosis_text'))
            ClassicEditor.create(document.querySelector('#ot_note_text'))
            ClassicEditor.create(document.querySelector('#course_in_hospital_text'))
            ClassicEditor.create(document.querySelector('#discharge_advice_text'))
            ClassicEditor.create(document.querySelector('#investigation_text'))
            ClassicEditor.create(document.querySelector('#urgent_care_text'))
            ClassicEditor.create(document.querySelector('#diet_advice_text'))

        })

        const container = document.getElementById('medContainer')

        document.getElementById('addMedBtn').onclick = function() {
            showMedLoader()
            setTimeout(() => {
                // existing add row logic
                hideMedLoader();
            }, 100);
            const index = container.querySelectorAll('.med-row').length + 1;

            const html = `
                <div class="med-row" data-index="${index}">
                    <div class="medicine-row-header d-flex justify-content-between align-items-center">
                        <span class="medicine-row-number">
                            <i class="bi bi-capsule-pill"></i> Medicine <span
                                class="badge bg-primary">${index}</span>
                        </span>
                        <button type="button"
                            class="btn btn-danger btn-sm mt-2 remove-med btn-remove-medicine">
                            <i class="bi bi-trash remove-med"></i>
                        </button>
                    </div>
                    <div class="row g-3">

                        <div class="col-md-2">
                            <label>Medicine</label>
                            <select class="med-medicine" name="meds[]"></select>
                        </div>

                        <div class="col-md-2">
                            <label>Type</label>
                            <select class="med-types" name="med_types[]">
                                    <option value="">Select Medicine Type</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Capsule">Capsule</option>
                                    <option value="Syrup">Syrup</option>
                                    <option value="Injection">Injection</option>
                                    <option value="Ointment">Ointment</option>
                                    <option value="Powder">Powder</option>
                                    <option value="Drop">Drop</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Interval</label>
                            <select class="med-interval" name="med_interval[]"></select>
                        </div>

                        <div class="col-md-3">
                            <label>Duration</label>
                            <select class="med-duration" name="med_duration[]"></select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control med-date py-2" name="med_date[]" />
                        </div>
                    </div>
                </div>
            `

            container.insertAdjacentHTML('beforeend', html)

            initTomSelects(container.lastElementChild)

        }

        document.addEventListener('click', function(e) {

            if (e.target.classList.contains('remove-med')) {
                e.target.closest('.med-row').remove()
            }

        })
    </script>
    <script>
        document.getElementById('patientDischargeUpdateForm').addEventListener('submit', function(e) {

            const rows = document.querySelectorAll('.med-row');

            rows.forEach(row => {
                const med = row.querySelector('.med-medicine')?.value;

                if (!med) {
                    row.remove(); // remove empty row
                }
            });
            const isFinalSubmit = e.submitter && e.submitter.id === 'final-submit';
            document.getElementById('is-Draft').value = isFinalSubmit ? "false" : "true";
            // ✅ IMPORTANT FIX
            const form = this;
            if (document.querySelectorAll('.med-row').length === 0) {

                // create hidden empty arrays
                ['meds', 'med_types', 'med_interval', 'med_duration', 'med_date'].forEach(name => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name + '[]';
                    input.value = '';
                    form.appendChild(input);
                });

            }

        })
    </script>
@endsection
