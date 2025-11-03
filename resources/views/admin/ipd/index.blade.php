@extends('layouts.adminLayout')

@section('content')
    <style>
        .nav-tabs .nav-link.active {
            background-color: #750096 !important;
            color: #f8f9fa !important;
            font-weight: 600 !important;
        }
    </style>
    <div class="container">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header d-flex justify-content-between align-items-center align-items-sm-center justify-content-between flex-sm-row"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> OPD</h5>
                <div class="text-end d-flex">
                    <ul class="nav nav-tabs">
                        <li class="nav-item" style="border-bottom:0">
                            <a class="nav-link mb-0 {{ @$isIpdTab ? 'active' : '' }} text-white"
                                href="{{ route('ipd', array_merge(request()->except('tab'), ['tab' => 'ipd'])) }}">
                                IPD List
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 {{ @!$isIpdTab ? 'active' : '' }} text-white"
                                href="{{ route('ipd', array_merge(request()->except('tab'), ['tab' => 'discharge'])) }}">
                                Discharged Patient
                            </a>
                        </li>
                    </ul>

                </div>
            </div>

            <div class="card-body">

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}</div>
                @endif
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div
                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <form action="{{ route('ipd') }}" method="GET">
                                            <div class="d-flex align-items-center">
                                                <div class="input-icon-start position-relative me-2">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-search"></i>
                                                    </span>
                                                    <input type="text" id="language-search" name="search"
                                                        value="{{ request('search') }}" class="form-control shadow-sm"
                                                        placeholder="Search">
                                                </div>
                                                <div>
                                                    <button class="btn btn-primary" type="submit">Search</button>
                                                </div>
                                            </div>
                                        </form>
                                        <form action="" style="width: 300px;">
                                            <div class="input-group shadow-sm">
                                                <label class="input-group-text" for="inputGroupSelect01">Consultant</label>
                                                <select class="form-select" id="inputGroupSelect01">
                                                    <option selected>Select</option>
                                                    @foreach ($doctors as $doctor)
                                                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </form>
                                        <div>
                                            <button class="btn btn-outline-primary" type="button"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop"
                                                aria-controls="offcanvasTop">Apply
                                                Filter</button>
                                        </div>
                                    </div>

                                    <div class="text-end d-flex">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#createIpdModal">Appoint Patient</button>
                                    </div>
                                </div>
                                @if ($isIpdTab)
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>IPD No.</th>
                                                    <th>Patient Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Gender</th>
                                                    <th>Consultant</th>
                                                    <th>Bed</th>
                                                    <th>Credit Limit (INR)</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ipd as $ipdDetails)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td><a href="#"
                                                                class="text-primary">{{ $ipdDetails->ipd_no }}</a>
                                                        </td>
                                                        <td>{{ $ipdDetails->patient->patient_name ?? '-' }}</td>
                                                        <td>{{ $ipdDetails->patient->mobileno ?? '-' }}</td>
                                                        <td>{{ $ipdDetails->patient->email ?? '-' }}</td>
                                                        <td>{{ $ipdDetails->patient->gender ?? '-' }}</td>
                                                        <td>{{ $ipdDetails->doctor->name ?? '-' }}</td>
                                                        <td><span class="badge"
                                                                style="background-color: {{ $ipdDetails->bedGroup->color }}">
                                                                {{ $ipdDetails->bedDetail->name . ' - ' . $ipdDetails->bedGroup->name . '-' . $ipdDetails->bedGroup->floorDetail->name ?? '-' }}</span>
                                                        </td>
                                                        <td>{{ $ipdDetails->credit_limit }}</td>
                                                        <td>
                                                            <a href="{{ route('ipd.edit', [$ipdDetails->id]) }}"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                <i class="ti ti-pencil"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Address</th>
                                                    <th>Gender</th>
                                                    <th>Consultant</th>
                                                    <th>Discharged Date</th>
                                                    <th>Net Amount (INR)</th>
                                                    <th>Tax (INR)</th>
                                                    <th>Total (INR)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ipd as $ipdDetails)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td><a href="#"
                                                                class="text-primary">{{ $ipdDetails->patient->patient_name }}</a>
                                                        </td>
                                                        <td>{{ $ipdDetails->patient->mobileno }}</td>
                                                        <td>{{ $ipdDetails->patient->email }}</td>
                                                        <td>{{ $ipdDetails->patient->address }}</td>
                                                        <td>{{ $ipdDetails->patient->gender }}</td>
                                                        <td>{{ $ipdDetails->doctor->name ?? '-' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($ipdDetails->discharged_date)->format('d-M-Y') ?? '-' }}
                                                        </td>
                                                        <td>{{ $ipdDetails->net_amount ?? 0 }}</td>
                                                        <td>{{ $ipdDetails->tax ?? 0 }}</td>
                                                        <td>{{ $ipdDetails->amount ?? 0 }}</td>


                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                @endif
                            </div>

                            <div class="offcanvas offcanvas-top" style="height: fit-content;" tabindex="-1"
                                id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
                                <div class="offcanvas-header justify-content-center">
                                    <h4 class="offcanvas-title m-auto font-weight-bold" id="offcanvasTopLabel">FILTERS</h4>
                                </div>
                                <div class="offcanvas-body">
                                    <div class="filter-section pb-3 rounded">
                                        <form action="{{ request()->routeIs('opd') ? route('opd') : route('opd') }}"
                                            method="GET" class="text-center" id="searchForm">
                                            <div
                                                class="d-flex flex-column flex-md-row gap-2 align-items-center justify-content-center">
                                                <!-- Filter Type Selector -->
                                                <div class="align-items-center">
                                                    <label for="filterType" class="form-label font-weight-bold">Select
                                                        Filter
                                                        Type</label>
                                                    <select id="filterType" class="form-select" name="filter_type"
                                                        style="width: 250px;">
                                                        <option value="dateRange"
                                                            {{ request('filter_type') == 'dateRange' ? 'selected' : '' }}>
                                                            Date
                                                            Range
                                                        </option>
                                                        <option value="monthly"
                                                            {{ request('filter_type') == 'monthly' ? 'selected' : '' }}>
                                                            Monthly</option>
                                                        <option value="weekly"
                                                            {{ request('filter_type') == 'weekly' ? 'selected' : '' }}>
                                                            Weekly</option>
                                                    </select>
                                                </div>

                                                <!-- Date Range Filter -->
                                                <div id="dateRangeFilter">
                                                    <div
                                                        class="d-flex flex-column flex-md-row gap-2 align-items-center filter-group">
                                                        <div>
                                                            <label class="form-label">From</label>
                                                            <input type="date" class="form-control" name="fromDate"
                                                                value="{{ request('fromDate') }}" />
                                                        </div>
                                                        <div>
                                                            <label class="form-label">To</label>
                                                            <input type="date" class="form-control" name="toDate"
                                                                value="{{ request('toDate') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Monthly Filter -->
                                                <div id="monthlyFilter" class="d-none">
                                                    <div class="filter-group">
                                                        <label class="form-label text-nowrap">Select Month</label>
                                                        <input type="month" class="form-control" name="monthFilter"
                                                            value="{{ request('monthFilter') }}" />
                                                    </div>
                                                </div>
                                                <!-- Weekly Filter -->
                                                <div id="weeklyFilter" class="d-none">
                                                    <div class="filter-group">
                                                        <label class="form-label text-nowrap">Select Week</label>
                                                        <input type="week" class="form-control" name="weekFilter"
                                                            value="{{ request('weekFilter') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 d-flex gap-2 mx-auto mt-3">
                                                <button type="submit" class="btn cmn_btn btn-primary w-100">
                                                    <i class="fas fa-filter me-1"></i> Filter
                                                </button>
                                                <a href="{{ request()->routeIs('opd') ? route('opd') : route('opd') }}"
                                                    class="btn btn-outline-dark w-100">
                                                    Reset
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    {{-- create OPD modal --}}
    @include('components.modals.ipd-create-modal')

    {{-- filters --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterTypeSelect = document.getElementById('filterType');
            const dateRangeFilter = document.getElementById('dateRangeFilter');
            const monthlyFilter = document.getElementById('monthlyFilter');
            const weeklyFilter = document.getElementById('weeklyFilter');

            const fromDateInput = document.querySelector('input[name="fromDate"]');
            const toDateInput = document.querySelector('input[name="toDate"]');
            const monthFilterInput = document.querySelector('input[name="monthFilter"]');
            const weekFilterInput = document.querySelector('input[name="weekFilter"]');

            const selectedFilter = "{{ request('filter_type', 'dateRange') }}";
            filterTypeSelect.value = selectedFilter;

            function showSelectedFilter(filterType) {
                dateRangeFilter.classList.add('d-none');
                monthlyFilter.classList.add('d-none');
                weeklyFilter.classList.add('d-none');

                if (filterType === 'monthly') {
                    monthlyFilter.classList.remove('d-none');
                } else if (filterType === 'weekly') {
                    weeklyFilter.classList.remove('d-none');
                } else {
                    dateRangeFilter.classList.remove('d-none');
                }
            }

            function resetOtherFilters(filterType) {
                if (filterType !== 'dateRange') {
                    fromDateInput.value = '';
                    toDateInput.value = '';
                }
                if (filterType !== 'monthly') {
                    monthFilterInput.value = '';
                }
                if (filterType !== 'weekly') {
                    weekFilterInput.value = '';
                }
            }

            // Initial show (preserve values on page load)
            showSelectedFilter(selectedFilter);

            // On filter type change (manual interaction)
            filterTypeSelect.addEventListener('change', function() {
                const selected = this.value;
                resetOtherFilters(selected);
                showSelectedFilter(selected);
            });
        });
    </script>


    {{-- <script>
        // const editButton = document.querySelector('.edit-opd')
        // console.log(editButton);

        function openEditModal() {
            const editOpdModal = document.getElementById('editOpdModal');
            console.log(editOpdModal);

            const symptomTypeSelect = document.getElementById('symptoms_type');
            const symptomTitleSelect = document.getElementById('symptoms_title');

            let selectedTypeIds = [];
            let preselectedTitleIds = [];

            // 🟢 When the modal is about to open
            editOpdModal.addEventListener('show.bs.modal', async function(event) {
                const button = event.relatedTarget;
                const opdId = button.getAttribute('data-id');

                // Clear previous options
                symptomTypeSelect.innerHTML = '<option value="">Loading...</option>';
                symptomTitleSelect.innerHTML =
                    '<option value="">Select symptom types first...</option>';

                try {
                    const baseUrl = "{{ route('opd.edit', ['id' => 'ID']) }}";
                    const finalUrl = baseUrl.replace('ID', opdId);
                    const response = await fetch(finalUrl);
                    const data = await response.json();

                    // Extract preselected values
                    const opd = data.opd;
                    const symptoms = data.symptoms;
                    const allTypes = data.symptomTypes;

                    selectedTypeIds = opd.symptoms_type ? opd.symptoms_type.split(',').map(Number) : [];
                    preselectedTitleIds = opd.symptoms_title ? opd.symptoms_title.split(',').map(
                        Number) : [];

                    console.log(selectedTypeIds);

                    // 🟢 Populate Symptom Types dropdown
                    symptomTypeSelect.innerHTML = '';
                    allTypes.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.name;
                        if (selectedTypeIds.includes(type.id)) option.selected = true;
                        symptomTypeSelect.appendChild(option);
                    });

                    // 🟢 Load and populate symptom titles for selected types
                    await loadSymptomsByTypes(selectedTypeIds);

                    // 🟢 Set preselected titles
                    for (const option of symptomTitleSelect.options) {
                        if (preselectedTitleIds.includes(parseInt(option.value))) {
                            option.selected = true;
                        }
                    }

                } catch (error) {
                    console.error("Error fetching OPD details:", error);
                }
            });

            // 🟢 When symptom types change
            symptomTypeSelect.addEventListener('change', async function() {
                const selected = Array.from(this.selectedOptions).map(opt => parseInt(opt.value));
                selectedTypeIds = selected;
                await loadSymptomsByTypes(selectedTypeIds);
            });

            // 🟢 Function to fetch and update symptoms based on selected types
            async function loadSymptomsByTypes(typeIds) {
                if (!typeIds.length) {
                    symptomTitleSelect.innerHTML = '<option value="">Select symptom types first...</option>';
                    return;
                }

                try {
                    const response = await fetch("{{ route('getSymptoms') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            typeIds.join(',')
                        })
                    });
                    const data = await response.json();

                    symptomTitleSelect.innerHTML = '';
                    if (data.length === 0) {
                        symptomTitleSelect.innerHTML = '<option value="">No symptoms found</option>';
                        return;
                    }

                    data.forEach(symptom => {
                        const option = document.createElement('option');
                        option.value = symptom.id;
                        option.textContent = symptom.name;
                        if (preselectedTitleIds.includes(symptom.id)) option.selected = true;
                        symptomTitleSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error("Error fetching symptoms:", error);
                }
            }

            // 🧹 Fix focus warning when modal closes
            editOpdModal.addEventListener('hide.bs.modal', () => {
                if (document.activeElement && editOpdModal.contains(document.activeElement)) {
                    document.activeElement.blur();
                }
            });
        }
    </script> --}}
@endsection
