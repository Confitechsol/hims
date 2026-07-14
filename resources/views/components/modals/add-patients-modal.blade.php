<style>
    .unit-select {
        flex: 0 0 20%;
        /* fixed 20% width */
        max-width: 20%;
    }

    .select2-container--default .select2-selection--multiple {
        padding: 0.625rem 0.875rem;
        /* adjust as needed */
        border-radius: 8px;
    }
    
</style>

<div class="modal fade use-select2" id="add_patient" tabindex="-1" aria-labelledby="addSpecializationLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content modal-xl">
            <div class="modal-header modal-xl rounded-0"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="modal-title" id="addSpecializationLabel">Add New
                    Patient
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ipdForm" action="{{ route('patient-store') }}" method="POST">
                    @csrf


                    <div class="row p-4 mx-1 gy-3">

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name*</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Guardian Name --}}
                        <div class="col-md-6">
                            <label for="guardian_name" class="form-label">Guardian Name*</label>
                            <input type="text" id="guardian_name" name="guardian_name"
                                class="form-control @error('guardian_name') is-invalid @enderror"
                                value="{{ old('guardian_name') }}" />
                            @error('guardian_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Guardian Phone --}}
                        <div class="col-md-6">
                            <label for="guardian_phone" class="form-label">Guardian Phone No.*</label>
                            <input type="text" id="guardian_phone" name="guardian_phone"
                                class="form-control @error('guardian_phone') is-invalid @enderror"
                                value="{{ old('guardian_phone') }}" />
                            @error('guardian_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Guardian Relation --}}
                        <div class="col-md-6">
                            <label for="guardian_relation" class="form-label">Guardian Relationship*</label>
                            <input type="text" id="guardian_relation" name="guardian_relation"
                                class="form-control @error('guardian_relation') is-invalid @enderror"
                                value="{{ old('guardian_relation') }}" />
                            @error('guardian_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Emergency Contact --}}
                        <div class="col-md-6">
                            <label for="emergency_contact_no" class="form-label">Emergency Contact No.*</label>
                            <input type="text" id="emergency_contact_no" name="emergency_contact_no"
                                class="form-control @error('emergency_contact_no') is-invalid @enderror"
                                value="{{ old('emergency_contact_no') }}" />
                            @error('emergency_contact_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Gender + DOB + Age --}}
                        <div class="col-md-6">
                            <div class="row">

                                {{-- Gender --}}
                                <div class="col-md-3">
                                    <label for="gender" class="form-label">Gender*</label>
                                    <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                        <option value="">Select</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="Others" {{ old('gender') == 'Others' ? 'selected' : '' }}>Others
                                        </option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- DOB --}}
                                <div class="col-md-4">
                                    <label for="birth_date" class="form-label">Date of Birth</label>
                                    <input type="date" id="birth_date" name="birth_date"
                                        class="form-control @error('birth_date') is-invalid @enderror"
                                        value="{{ old('birth_date') }}" />
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Age --}}
                                <div class="col-sm-5">
                                    <label class="form-label">Age*
                                        (yy-mm-dd)</label>
                                    <div style="clear: both; overflow: hidden;">
                                        <input type="numeric" name="age[year]" id="age_year" placeholder="YY"
                                            value="{{ old('age.year') }}"
                                            class="form-control patient_age_year @error('age.year') is-invalid @enderror"
                                            style="width: 30%; float: left;" />
                                        <input type="numeric" name="age[month]" id="age_month" placeholder="MM"
                                            value="{{ old('age.month') }}"
                                            class="form-control patient_age_month @error('age.month') is-invalid @enderror"
                                            style="width: 36%; float: left; margin-left: 4px;" />
                                        <input type="numeric" name="age[day]" id="age_day" placeholder="DD"
                                            value="{{ old('age.day') }}"
                                            class="form-control patient_age_day @error('age.day') is-invalid @enderror"
                                            style="width: 26%; float: left; margin-left: 4px;" />
                                    </div>
                                    @error('age.year')
                                        <div class="invalid-feedback d-block">Year:
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    @error('age.month')
                                        <div class="invalid-feedback d-block">Month:
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    @error('age.day')
                                        <div class="invalid-feedback d-block">Day:
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- Blood Group + Marital Status + Photo --}}
                        <div class="col-md-6">
                            <div class="row">

                                {{-- Blood Group --}}
                                <div class="col-md-3">
                                    <label for="blood_group" class="form-label">Blood Group</label>
                                    <select name="blood_group"
                                        class="form-control @error('blood_group') is-invalid @enderror">
                                        <option value="">Select</option>
                                        @foreach ($bloodGroups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ old('blood_group') == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('blood_group')
                                        <div class="invalid-feedback">{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Marital Status --}}
                                <div class="col-md-3">
                                    <label for="marital_status" class="form-label">Marital
                                        Status*</label>
                                    <select name="marital_status"
                                        class="form-control @error('marital_status') is-invalid @enderror">
                                        <option value="">Select</option>
                                        <option value="Single"
                                            {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="unmarried"
                                            {{ old('marital_status') == 'Unmarried' ? 'selected' : '' }}>Unmarried</option>
                                            
                                        <option value="Married"
                                            {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Widowed"
                                            {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated"
                                            {{ old('marital_status') == 'Separated' ? 'selected' : '' }}>Separated
                                        </option>
                                        <option value="Not Specified"
                                            {{ old('marital_status') == 'Not Specified' ? 'selected' : '' }}>Not
                                            Specified</option>
                                    </select>
                                    @error('marital_status')
                                        <div class="invalid-feedback">{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Patient Photo --}}
                                <div class="col-md-6">
                                    <label for="file" class="form-label">Patient
                                        Photo</label>
                                    <input class="form-control @error('file') is-invalid @enderror" type="file"
                                        name="file" id="file" />
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- Phone + Email --}}
                        <div class="col-md-6">
                            <div class="row">

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" id="phone" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" />
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="col-md-3">
                            <label for="address" class="form-label">Address*</label>
                            <input type="text" id="address" name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                value="{{ old('address') }}" />
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Area --}}
                        {{-- <div class="col-md-3">
                            <label for="area" class="form-label">Area</label>
                            <div class="input-group">
                                <select id="area" name="area" class="form-select @error('area') is-invalid @enderror">
                                    <option value="">Select Area</option>
                                    @foreach ($areas ?? [] as $area)
                                        <option value="{{ $area->id }}" {{ old('area') == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}


                         {{-- police_station --}}
                        <div class="col-md-3">
                            <label for="police_station" class="form-label">Police Station*</label>
                            <input type="text" id="police_station" name="police_station"
                                class="form-control @error('police_station') is-invalid @enderror"
                                value="{{ old('police_station') }}" />
                            @error('police_station')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Area --}}
                        <div class="col-md-3">
                            <label for="area" class="form-label">Area*</label>
                            <input type="text" id="area" name="area"
                                class="form-control @error('area') is-invalid @enderror"
                                value="{{ old('area') }}" />
                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Area --}}
                        <div class="col-md-3">
                            <label for="pin_code" class="form-label">Pin Code*</label>
                            <input type="text" id="pin_code" name="pin_code"
                                class="form-control @error('pin_code') is-invalid @enderror"
                                value="{{ old('pin_code') }}" />
                            @error('pin_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                       {{-- State --}}
                        <div class="col-md-3">
                            <label class="form-label">State*</label>
                            <select id="stateDropdown" name="state_id" class="form-control">
                            <option value="">Select State</option>
                        </select>
                        </div>
                        
                        {{-- District --}}
                        <div class="col-md-3">
                            <label class="form-label">District*</label>
                            <select id="districtDropdown" name="district_id" class="form-control">
                            <option value="">Select District</option>
                        </select>
                        </div>
                        
    
                        {{-- District --}}
                        {{-- <div class="col-md-3">
                            <label for="district" class="form-label">District</label>
                            <input type="text" id="district" name="district_name"
                                class="form-control @error('district') is-invalid @enderror"
                                value="{{ old('district') }}" readonly />
                                <input type="hidden"
                            id="district_id"
                            name="district">
                            @error('district')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        {{-- State --}}
                        {{-- <div class="col-md-3">
                            <label class="form-label">State</label>
                            <input type="text" id="state" name="state_name"
                                class="form-control"
                                readonly>
                        </div>
                        <input type="hidden"
                            id="state_id"
                            name="state"> --}}
                        
                        
                        {{-- religion --}}
                        <div class="col-md-3">
                            <label for="religion" class="form-label">Religion</label>
                            <select id="religion" name="religion"
                                class="form-select @error('religion') is-invalid @enderror">
                                <option value="">Select</option>
                                <option value="Hinduism" {{ old('religion') == 'Hinduism' ? 'selected' : '' }}>
                                    Hinduism
                                </option>
                                <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : '' }}>Islam
                                </option>
                                <option value="Christianity"
                                    {{ old('religion') == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                                <option value="Sikhism" {{ old('religion') == 'Sikhism' ? 'selected' : '' }}>Sikhism
                                </option>
                                <option value="Buddhism" {{ old('religion') == 'Buddhism' ? 'selected' : '' }}>
                                    Buddhism
                                </option>
                                <option value="Jainism" {{ old('religion') == 'Jainism' ? 'selected' : '' }}>Jainism
                                </option>
                                <option value="Other" {{ old('religion') == 'Other' ? 'selected' : '' }}>Other
                                </option>
                                @error('religion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </select>
                        </div>
                        {{-- Allergies --}}
                        <div class="col-md-3">
                            <label for="allergies" class="form-label">Any Known
                                Allergies</label>
                            <input type="text" id="allergies" name="allergies"
                                class="form-control @error('allergies') is-invalid @enderror"
                                value="{{ old('allergies') }}" />
                            @error('allergies')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Languages Speak --}}
                        <div class="col-md-3">
                            <label for="languages_speak" class="form-label">Languages Known*</label>
                            {{-- <input type="text" id="languages_speak" name="languages_speak"
                                class="form-control @error('languages_speak') is-invalid @enderror"
                                value="{{ old('languages_speak') }}" /> --}}
                            <select id="languages_known" name="languages_known[]"
                                class="form-control @error('languages_known') is-invalid @enderror" multiple>
                                <option value="Bengali"
                                    {{ collect(old('languages_known'))->contains('Bengali') ? 'selected' : '' }}>
                                    Bengali</option>
                                <option value="Hindi"
                                    {{ collect(old('languages_known'))->contains('Hindi') ? 'selected' : '' }}>
                                    Hindi</option>
                                <option value="English"
                                    {{ collect(old('languages_known'))->contains('English') ? 'selected' : '' }}>
                                    English</option>
                                <option value="Urdu"
                                    {{ collect(old('languages_known'))->contains('Urdu') ? 'selected' : '' }}>
                                    Urdu</option>
                                <!-- Add more languages as needed -->
                            </select>
                            @error('languages_known')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Newspaper Preference --}}
                        <div class="col-md-3">
                            <label for="newspaper_preference" class="form-label">Newspaper Preference*</label>
                            <input type="text" id="newspaper_preference" name="newspaper_preference"
                                class="form-control @error('newspaper_preference') is-invalid @enderror"
                                value="{{ old('newspaper_preference') }}" />
                            @error('newspaper_preference')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Height --}}
                        <div class="col-md-3">
                            <label for="height_value" class="form-label">Height</label>

                            <div class="input-group d-flex">
                                <input type="text" id="height_value"
                                    class="form-control @error('height') is-invalid @enderror"
                                    placeholder="Enter height" value="{{ old('height') }}">

                                <select id="height_unit" class="form-select unit-select">
                                    <option value="ft">ft</option>
                                    <option value="cm">cm</option>
                                </select>
                            </div>

                            <input type="hidden" name="height" id="height">
                            @error('height')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Weight --}}
                        <div class="col-md-3">
                            <label for="weight_value" class="form-label">Weight</label>

                            <div class="input-group d-flex">
                                <input type="text" id="weight_value"
                                    class="form-control @error('weight') is-invalid @enderror"
                                    placeholder="Enter weight" value="{{ old('weight') }}">

                                <select id="weight_unit" class="form-select unit-select">
                                    <option value="kg">kg</option>
                                    <option value="lbs">lbs</option>
                                </select>
                            </div>

                            <input type="hidden" name="weight" id="weight">
                            @error('weight')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="nationality" class="form-label">Nationality*</label>
                            <select id="nationality" class="form-select @error('nationality') is-invalid @enderror"
                                name="nationality">
                                <option value="">Select your nationality</option>
                                <option value="Indian" {{ old('nationality') == 'Indian' ? 'selected' : '' }}>Indian
                                </option>
                                <option value="Bangladeshi" {{ old('nationality') == 'Bangladeshi' ? 'selected' : '' }}>Bangladeshi
                                </option>
                                <option value="American" {{ old('nationality') == 'American' ? 'selected' : '' }}>
                                    American</option>
                                <option value="British" {{ old('nationality') == 'British' ? 'selected' : '' }}>
                                    British</option>
                            </select>
                            @error('nationality')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- Temperature --}}
                        <div class="col-md-4">
                            <label for="temperature_value" class="form-label">Temperature</label>

                            <div class="input-group">
                                <!-- Numeric input -->
                                <input type="text" id="temperature_value" placeholder="Enter Temperature"
                                    class="form-control @error('temperature') is-invalid @enderror"
                                    value="{{ old('temperature') }}" />

                                <!-- Unit dropdown -->
                                <select id="temperature_unit" class="form-select unit-select">
                                    <option value="°C">°C</option>
                                    <option value="°F">°F</option>
                                </select>
                            </div>

                            <!-- Hidden field that will store final value (37°C / 98.6°F) -->
                            <input type="hidden" name="temperature" id="temperature">

                            @error('temperature')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- National ID --}}
                        <div class="col-md-6">
                            <label for="national_id_number" class="form-label">Adhaar Card or PAN Card</label>
                            <input type="text" id="national_id_number" name="national_id_number"
                                class="form-control @error('national_id_number') is-invalid @enderror"
                                value="{{ old('national_id_number') }}" />
                            @error('national_id_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Remarks --}}
                        <div class="col-md-6">
                            <label for="remarks" class="form-label">Remarks</label>
                            <input type="text" id="remarks" name="remarks"
                                class="form-control @error('remarks') is-invalid @enderror"
                                value="{{ old('remarks') }}" />
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                         {{-- Occupation --}}
                        <div class="col-md-6">
                            <label for="occupation" class="form-label">Occupation</label>
                            <input type="text" id="occupation" name="occupation"
                                class="form-control @error('occupation') is-invalid @enderror"
                                value="{{ old('occupation') }}" />
                            @error('occupation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Department --}}
                        <div class="col-md-5">
                            <label class="form-label">Department</label>
                            <select id="departmentDropdown" name="department_ids[]" class="form-control" multiple>
                                <option value="">Select Department</option>
                            </select>
                        </div>
                       

                    </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Patient</button>
                    </div>
                </form>
            </div>
    </div>
</div>
<!-- Include jQuery and Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

const baseUrl = "{{ route('get.district', ':id') }}";

document.getElementById('area').addEventListener('change', function () {

    let url = baseUrl.replace(':id', this.value);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            document.getElementById('district').value = data.district ?? '';
            document.getElementById('district_id').value = data.district_id ?? '';
            document.getElementById('state_id').value = data.state_id ?? '';
            document.getElementById('state').value = data.state ?? '';
        })
        .catch(error => console.error(error));

});

</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        const form = document.getElementById("ipdForm");

        form.addEventListener("submit", function(e) {

            // HEIGHT
            const hVal = document.getElementById("height_value")?.value ?? "";
            const hUnit = document.getElementById("height_unit")?.value ?? "";
            document.getElementById("height").value = (hVal && hUnit) ? (hVal + hUnit) : "";

            // WEIGHT
            const wVal = document.getElementById("weight_value")?.value ?? "";
            const wUnit = document.getElementById("weight_unit")?.value ?? "";
            document.getElementById("weight").value = (wVal && wUnit) ? (wVal + wUnit) : "";

            // TEMPERATURE
            const tVal = document.getElementById("temperature_value")?.value ?? "";
            const tUnit = document.getElementById("temperature_unit")?.value ?? "";
            document.getElementById("temperature").value = (tVal && tUnit) ? (tVal + tUnit) : "";

            console.log("FORM SUBMITTED: height =", document.getElementById("height").value);

        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const birthDateInput = document.getElementById('birth_date');
        const ageYearInput = document.getElementById('age_year');
        const ageMonthInput = document.getElementById('age_month');
        const ageDayInput = document.getElementById('age_day');

        // ------------------ DOB → AUTO CALCULATE AGE ------------------
        birthDateInput.addEventListener('change', function() {
            const birthDate = new Date(this.value);
            if (!this.value || isNaN(birthDate)) {
                ageYearInput.value = '';
                ageMonthInput.value = '';
                ageDayInput.value = '';
                return;
            }

            const today = new Date();
            let years = today.getFullYear() - birthDate.getFullYear();
            let months = today.getMonth() - birthDate.getMonth();
            let days = today.getDate() - birthDate.getDate();

            if (days < 0) {
                months--;
                const prevMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                days += prevMonth.getDate();
            }

            if (months < 0) {
                years--;
                months += 12;
            }

            ageYearInput.value = years;
            ageMonthInput.value = months;
            ageDayInput.value = days;
        });

        // ------------------ AGE → AUTO CALCULATE DOB ------------------
        function updateDOB() {
            const years = parseInt(ageYearInput.value) || 0;
            const months = parseInt(ageMonthInput.value) || 0;
            const days = parseInt(ageDayInput.value) || 0;

            // If no age entered → do nothing
            if (!years && !months && !days) return;

            const today = new Date();

            // Subtract entered values
            let dob = new Date(
                today.getFullYear() - years,
                today.getMonth() - months,
                today.getDate() - days
            );

            // If month+day not given → approximate DOB = Jan 1st of that year
            if (!ageMonthInput.value && !ageDayInput.value) {
                dob = new Date(today.getFullYear() - years, 0, 1); // 1st Jan
            }

            // Format YYYY-MM-DD
            const yyyy = dob.getFullYear();
            const mm = String(dob.getMonth() + 1).padStart(2, '0');
            const dd = String(dob.getDate()).padStart(2, '0');

            birthDateInput.value = `${yyyy}-${mm}-${dd}`;
        }

        // Trigger when age fields are typed/changed
        ageYearInput.addEventListener('input', updateDOB);
        ageMonthInput.addEventListener('input', updateDOB);
        ageDayInput.addEventListener('input', updateDOB);

    });
    $(document).on('shown.bs.modal', '.use-select2', function() {
        const $modal = $(this);
        const $tpaSelect = $modal.find('#tpa');
        const $tpaIdInput = $modal.find('#tpa_id');

        if (!$tpaSelect.length) return;

        // Reset select
        $tpaSelect.html('<option value="">Loading...</option>');

        fetch("{{ route('getOrganizations') }}")
            .then(response => response.json())
            .then(data => {
                window.organizationsData = data;

                $tpaSelect.html('<option value="">Select</option>');

                data.forEach(org => {
                    const selected = "{{ old('tpa') }}" == org.id ? 'selected' : '';
                    $tpaSelect.append(
                        `<option value="${org.id}" ${selected}>${org.organisation_name}</option>`
                    );
                });

                // Init Select2 (only once)
                if (!$tpaSelect.hasClass('select2-hidden-accessible')) {
                    $tpaSelect.select2({
                        dropdownParent: $modal,
                        width: '100%'
                    });
                }

                // Trigger change for old value
                $tpaSelect.trigger('change');
            })
            .catch(error => {
                console.error('Error fetching organizations:', error);
                $tpaSelect.html('<option value="">Error loading options</option>');
            });

        // Change event (Select2 compatible)
        $tpaSelect.off('change.select2sync').on('change.select2sync', function() {
            const selectedId = this.value;
            const selectedOrg = window.organizationsData?.find(
                org => org.id == selectedId
            );

            $tpaIdInput.val(selectedOrg ? selectedOrg.code : '');
        });
    });
    $(document).on('hidden.bs.modal', '.use-select2', function () {
    const $tpaSelect = $(this).find('#tpa');

    if ($tpaSelect.hasClass('select2-hidden-accessible')) {
        $tpaSelect.select2('destroy');
    }
});
    $('#add_patient').on('shown.bs.modal', function() {
        let select = $('#languages_known');
        if (!select.hasClass('select2-hidden-accessible')) {
            select.select2({
                dropdownParent: $('#add_patient'),
                placeholder: 'Select languages',
                allowClear: true,
                width: '100%'
            });
        }
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Fetch states from API
    fetch('{{url('/')}}/states')  // make sure this API route exists
        .then(response => response.json())
        .then(res => {
            const dropdown = document.getElementById('stateDropdown');

            if(res.status) {
                res.data.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state.id;    // your state id
                    option.textContent = state.name; // your state name
                    dropdown.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error fetching states:', error));
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const stateDropdown = document.getElementById('stateDropdown');
    const districtDropdown = document.getElementById('districtDropdown');

    // 1️⃣ Load all states on page load
    fetch('{{ url("/states") }}') // Make sure this API route returns JSON
        .then(response => response.json())
        .then(res => {
            if(res.status) {
                res.data.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state.id;
                    option.textContent = state.name;
                    stateDropdown.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error fetching states:', error));

    // 2️⃣ Load districts when state changes
    stateDropdown.addEventListener('change', function () {
        const stateId = this.value;

        if(!stateId) {
            districtDropdown.innerHTML = '<option value="">Select District</option>';
            return;
        }

        districtDropdown.innerHTML = '<option value="">Loading...</option>';

        fetch(`{{ url('/districts') }}?state_id=${stateId}`)
            .then(response => response.json())
            .then(res => {
                districtDropdown.innerHTML = '<option value="">Select District</option>';
                if(res.status && res.data.length > 0) {
                    res.data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        districtDropdown.appendChild(option);
                    });
                } else {
                    districtDropdown.innerHTML = '<option value="">No districts found</option>';
                }
            })
            .catch(error => {
                console.error('Error fetching districts:', error);
                districtDropdown.innerHTML = '<option value="">Error loading districts</option>';
            });
    });
});
// Populate department dropdown every time the modal is shown
$('#add_patient').on('shown.bs.modal', function() {
    const dropdown = document.getElementById('departmentDropdown');
    // Clear previous options except the placeholder
    dropdown.innerHTML = '<option value="">Select Department</option>';
    fetch('{{url('/')}}/departments')
        .then(response => response.json())
        .then(res => {
            if(res.status) {
                res.data.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department.id;
                    option.textContent = department.department_name;
                    dropdown.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error fetching departments:', error));
    // Re-initialize select2
    $('#departmentDropdown').select2({
        dropdownParent: $('#add_patient'),
        placeholder: 'Select Department',
        allowClear: true
    });
});
</script>