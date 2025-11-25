
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
                <form action="{{ route('patient-store') }}" method="POST">
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

                    <div class="row gy-3">

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
                                        <input type="text" name="age[year]" id="age_year" placeholder="YY"
                                            value="{{ old('age.year') }}"
                                            class="form-control patient_age_year @error('age.year') is-invalid @enderror"
                                            style="width: 30%; float: left;" />
                                        <input type="text" name="age[month]" id="age_month" placeholder="MM"
                                            value="{{ old('age.month') }}"
                                            class="form-control patient_age_month @error('age.month') is-invalid @enderror"
                                            style="width: 36%; float: left; margin-left: 4px;" />
                                        <input type="text" name="age[day]" id="age_day" placeholder="DD"
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

                        {{-- Allergies --}}
                        <div class="col-md-6">
                            <label for="allergies" class="form-label">Any Known
                                Allergies</label>
                            <input type="text" id="allergies" name="allergies"
                                class="form-control @error('allergies') is-invalid @enderror"
                                value="{{ old('allergies') }}" />
                            @error('allergies')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                            <input type="text" id="tpa_validity" name="tpa_validity"
                                class="form-control @error('tpa_validity') is-invalid @enderror"
                                value="{{ old('tpa_validity') }}" />
                            @error('tpa_validity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- National ID --}}
                        <div class="col-md-4">
                            <label for="national_id_number" class="form-label">National Identification
                                Number</label>
                            <input type="text" id="national_id_number" name="national_id_number"
                                class="form-control @error('national_id_number') is-invalid @enderror"
                                value="{{ old('national_id_number') }}" />
                            @error('national_id_number')
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
        //auto calculate age..............................................................................
        const birthDateInput = document.getElementById('birth_date');
        const ageYearInput = document.getElementById('age_year');
        const ageMonthInput = document.getElementById('age_month');
        const ageDayInput = document.getElementById('age_day');

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
    });
</script>
