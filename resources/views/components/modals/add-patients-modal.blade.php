<style>
    .unit-select {
    flex: 0 0 20%; /* fixed 20% width */
    max-width: 20%;
}
</style>

<div class="modal fade" id="add_patient" tabindex="-1" aria-labelledby="addSpecializationLabel" aria-hidden="true">
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
                    <!-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>There were some problems with your
                                input:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif -->

                    <div class="row p-4 mx-1 gy-3">

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Guardian Name --}}
                        <div class="col-md-6">
                            <label for="guardian_name" class="form-label">Guardian Name</label>
                            <input type="text" id="guardian_name" name="guardian_name"
                                class="form-control @error('guardian_name') is-invalid @enderror"
                                value="{{ old('guardian_name') }}" />
                            @error('guardian_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Guardian Phone --}}
                        <div class="col-md-6">
                            <label for="guardian_phone" class="form-label">Guardian Phone No.</label>
                            <input type="text" id="guardian_phone" name="guardian_phone"
                                class="form-control @error('guardian_phone') is-invalid @enderror"
                                value="{{ old('guardian_phone') }}" />
                            @error('guardian_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Guardian Relation --}}
                        <div class="col-md-6">
                            <label for="guardian_relation" class="form-label">Guardian Relationship</label>
                            <input type="text" id="guardian_relation" name="guardian_relation"
                                class="form-control @error('guardian_relation') is-invalid @enderror"
                                value="{{ old('guardian_relation') }}" />
                            @error('guardian_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Emergency Contact --}}
                        <div class="col-md-6">
                            <label for="emergency_contact_no" class="form-label">Emergency Contact No.</label>
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
                                    <label for="gender" class="form-label">Gender</label>
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
                                    <label class="form-label">Age
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
                                        @foreach($bloodGroups as $group)
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
                                        Status</label>
                                    <select name="marital_status"
                                        class="form-control @error('marital_status') is-invalid @enderror">
                                        <option value="">Select</option>
                                        <option value="Single"
                                            {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
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
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" id="address" name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                value="{{ old('address') }}" />
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Allergies --}}
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <label for="languages_speak" class="form-label">Languages Speak</label>
                            <input type="text" id="languages_speak" name="languages_speak"
                                class="form-control @error('languages_speak') is-invalid @enderror"
                                value="{{ old('languages_speak') }}" />
                            @error('languages_speak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Newspaper Preference --}}
                        <div class="col-md-4">
                            <label for="newspaper_preference" class="form-label">Newspaper Preference</label>
                            <input type="text" id="newspaper_preference" name="newspaper_preference"
                                class="form-control @error('newspaper_preference') is-invalid @enderror"
                                value="{{ old('newspaper_preference') }}" />
                            @error('newspaper_preference')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Height --}}
                        <div class="col-md-4">
                            <label for="height_value" class="form-label">Height</label>

                            <div class="input-group d-flex">
                                <input type="text" id="height_value" class="form-control @error('height') is-invalid @enderror"
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
                        <div class="col-md-4">
                            <label for="weight_value" class="form-label">Weight</label>

                            <div class="input-group d-flex">
                                <input type="text" id="weight_value" class="form-control @error('weight') is-invalid @enderror"
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


                        {{-- TPA --}}
                        <div class="col-md-4">
                            <label for="tpa" class="form-label">TPA</label>
                            <select id="tpa" name="tpa"
                                class="form-control @error('tpa') is-invalid @enderror">
                                <option value="">Loading...</option>
                            </select>
                            @error('tpa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TPA ID --}}
                        <div class="col-md-4">
                            <label for="tpa_id" class="form-label">TPA
                                Code</label>
                            <input type="text" id="tpa_id" name="tpa_id"
                                class="form-control @error('tpa_id') is-invalid @enderror"
                                value="{{ old('tpa_id') }}" />
                            @error('tpa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TPA Validity --}}
                        <div class="col-md-4">
                            <label for="tpa_validity" class="form-label">TPA
                                Validity</label>
                            <input type="date" id="tpa_validity" name="tpa_validity"
                                class="form-control @error('tpa_validity') is-invalid @enderror"
                                value="{{ old('tpa_validity') }}" />
                            @error('tpa_validity')
                                <div class="invalid-feedback">{{ $message }}</div>
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

                    </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Patient</button>
                    </div>
                </form>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("ipdForm");

    form.addEventListener("submit", function (e) {

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
        const tpaSelect = document.getElementById('tpa');
        const tpaIdInput = document.getElementById('tpa_id');
        tpaSelect.innerHTML = '<option value="">Loading...</option>';

        fetch("{{ route('getOrganizations') }}")
            .then(response => response.json())
            .then(data => {
                window.organizationsData = data;
                tpaSelect.innerHTML = '<option value="">Select</option>';
                data.forEach(org => {
                    const option = document.createElement('option');
                    option.value = org.id;
                    option.textContent = org.organisation_name;
                    if ("{{ old('tpa') }}" == org.id) {
                        option.selected = true;
                    }
                    tpaSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching organizations:', error);
                tpaSelect.innerHTML = '<option value="">Error loading options</option>';
            });

            // Listen for dropdown change
            tpaSelect.addEventListener('change', function() {
                const selectedId = this.value;
                const selectedOrg = window.organizationsData.find(org => org.id == selectedId);
                tpaIdInput.value = selectedOrg ? selectedOrg.code : '';
            });
      // Elements
const birthDateInput = document.getElementById('birth_date');
const ageYearInput = document.getElementById('age_year');
const ageMonthInput = document.getElementById('age_month');
const ageDayInput = document.getElementById('age_day');

// ------------------ DOB → AUTO CALCULATE AGE ------------------
birthDateInput.addEventListener('change', function () {
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
</script>
