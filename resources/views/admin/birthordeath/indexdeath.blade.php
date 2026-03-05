@extends('layouts.adminLayout')
@section('content')


    <div class="row px-5 py-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Death List</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    {{-- <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="input-icon-start position-relative me-2">
                                                <span class="input-icon-addon">
                                                    <i class="ti ti-search"></i>
                                                </span>
                                                <input onkeyup="dataSearch()" type="text" id="language-search" name="search"
                                                    class="form-control shadow-sm" placeholder="Search">
                                            </div>

                                        </div>

                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                                    data-bs-toggle="modal" data-bs-target="#add_tpa"><i
                                                        class="ti ti-plus me-1"></i>Add Expense</a>
                                            </div>
                                            <!-- First Modal -->

                                        </div>

                                    </div> --}}
                                    <x-table-actions.actions id="death" name="Death Record" />
                                    <!-- Table start -->
                                    <div class="table-responsive table-nowrap">
                                        <table class="table" id="birth">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Reference No</th>
                                                    <th>Case ID</th>
                                                    <th>Patient Name</th>
                                                    <th>Guardian Name</th>
                                                    <th>Gender</th>
                                                    <th>Death Date</th>
                                                    <th>Action</th>
                                                    <th>Generated Death Certificate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($deathReports as $report)
                                                                <tr>
                                                                    <td>{{$report->id}}</td>
                                                                    <td>{{$report->case_reference_id}}</td>
                                                                    <td>{{$report->patient->patient_name ?? '-'}}</td>
                                                                    <td>{{$report->guardian_name}}</td>
                                                                    <td>{{$report->patient->gender ?? '-' }}</td>
                                                                    <td>{{\Carbon\Carbon::parse($report->death_date)->format('d/m/Y h:i A') }}
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex">
                                                                            <button
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                                                data-patient_name="{{ $report->patient->patient_name ?? '' }}"
                                                                                data-patient_id="{{ $report->patient_id }}"
                                                                                data-case_id="{{ $report->case_reference_id}}"
                                                                                data-death_date="{{ \Carbon\Carbon::parse($report->death_date)->format('Y-m-d') }}"
                                                                                data-guardian_name="{{ $report->guardian_name }}"
                                                                                data-report="{{ $report->attachment_name }}"
                                                                                data-id="{{ $report->id }}"
                                                                                data-due_to_a="{{ $report->due_to_a }}"
                                                                                data-due_to_b="{{ $report->due_to_b }}"
                                                                                data-due_to_c="{{ $report->due_to_c }}"
                                                                                data-manner_of_death="{{ $report->manner_of_death }}"
                                                                                data-doctor_name="{{ $report->doctor->name ?? '' }}">
                                                                                <i class="ti ti-pencil"></i>
                                                                            </button>
                                                                            <form action="{{ route('death.delete', $report->id) }}"
                                                                                method="POST" style="display:inline;">
                                                                                @csrf
                                                                                @method('DELETE')

                                                                                <button type="submit"
                                                                                    onclick="return confirm('Are you sure you want to delete this record?')"
                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                                    <i class="ti ti-trash"></i>
                                                                                </button>
                                                                            </form>

                                                                    <td>
                                                                        <button type="button" class="btn btn-info btn-sm"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#birthCertModal{{ $report->id }}">
                                                                            Generated Death Certificate
                                                                        </button>

                                                                        <!-- Modal for Certificate Design (Rendered Directly) -->
                                                                        <div class="modal fade" id="birthCertModal{{ $report->id }}"
                                                                            tabindex="-1"
                                                                            aria-labelledby="birthCertModalLabel{{ $report->id }}"
                                                                            aria-hidden="true">
                                                                            <div class="modal-dialog modal-fullscreen">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title pe-2"
                                                                                            id="birthCertModalLabel{{ $report->id }}">Death
                                                                                            Certificate</h5>
                                                                                        <div class="d-flex gap-2">
                                                                                            <button class="btn btn-outline-primary btn-sm"
                                                                                                onclick="printCertificate('certificateContent{{ $report->id }}')"><i
                                                                                                    class="fa fa-print"></i> Print</button>
                                                                                            <button class="btn btn-outline-success btn-sm"
                                                                                                onclick="downloadCertificateAsImage('certificateContent{{ $report->id }}')"><i
                                                                                                    class="fa fa-download"></i> Download
                                                                                                Image</button>
                                                                                            <button class="btn btn-outline-danger btn-sm"
                                                                                                onclick="downloadCertificateAsPDF('certificateContent{{ $report->id }}')"><i
                                                                                                    class="fa fa-file-pdf"></i> Download
                                                                                                PDF</button>
                                                                                        </div>
                                                                                        <button type="button" class="btn-close"
                                                                                            data-bs-dismiss="modal"
                                                                                            aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div id="certificateContent{{ $report->id }}">
                                                                                            <style>
                                                                                                .certificate {
                                                                                                    width: 1000px;
                                                                                                    margin: 20px auto;
                                                                                                    background: #ffffff;
                                                                                                    padding: 40px;
                                                                                                    border-radius: 12px;
                                                                                                    box-shadow: 0 0 18px rgba(0, 0, 0, 0.15);
                                                                                                    position: relative;
                                                                                                }

                                                                                                .header {
                                                                                                    text-align: center;
                                                                                                    margin-bottom: 20px;
                                                                                                }

                                                                                                .logo-box {
                                                                                                    width: 200px;
                                                                                                    margin: 0px auto 50px;
                                                                                                }

                                                                                                h1 {
                                                                                                    font-size: 36px;
                                                                                                    margin-bottom: 3px;
                                                                                                    color: #750096;
                                                                                                }

                                                                                                h2 {
                                                                                                    font-size: 22px;
                                                                                                    margin-top: 30px;
                                                                                                    color: #750096;
                                                                                                    border-bottom: 3px solid #750096;
                                                                                                    padding-bottom: 6px;
                                                                                                }

                                                                                                .row {
                                                                                                    display: flex;
                                                                                                    justify-content: space-between;
                                                                                                    margin-top: 15px;
                                                                                                    gap: 20px;
                                                                                                }

                                                                                                .col {
                                                                                                    width: 48%;
                                                                                                }

                                                                                                label {
                                                                                                    font-weight: 600;
                                                                                                    font-size: 15px;
                                                                                                    color: #2c3e50;
                                                                                                    display: block;
                                                                                                    margin-bottom: 4px;
                                                                                                }

                                                                                                .line-data {
                                                                                                    font-size: 16px;
                                                                                                    padding: 8px 10px;
                                                                                                    border-bottom: 2px solid #750096;
                                                                                                    background: #e7c1f217;
                                                                                                    border-radius: 4px;
                                                                                                    margin-bottom: 12px;
                                                                                                }

                                                                                                .multi-data {
                                                                                                    font-size: 16px;
                                                                                                    padding: 8px 10px;
                                                                                                    border: 2px solid #750096;
                                                                                                    background: #e7c1f217;
                                                                                                    border-radius: 4px;
                                                                                                    height: 70px;
                                                                                                    margin-bottom: 15px;
                                                                                                }

                                                                                                .cut_list {
                                                                                                    border: dashed 2px #9C27B0;
                                                                                                }

                                                                                                .heading {
                                                                                                    color: #750096;
                                                                                                    padding-top: 12px;
                                                                                                    text-align: center;
                                                                                                }
                                                                                            </style>
                                                                                            <div class="certificate">
                                                                                                <div class="header">
                                                                                                    <div class="logo-box">
                                                                                                        <img src="{{ asset('assets/img/logo.png') }}"
                                                                                                            alt="COGNAIHEALTH">
                                                                                                    </div>
                                                                                                    <h1>Death Certificate</h1>
                                                                                                </div>

                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Patient Name</label>
                                                                                                        <div class="line-data">
                                                                                                            {{ $report->patient_name }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>Gender</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->patient->gender ?? '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Death Date</label>
                                                                                                        <div class="line-data">
                                                                                                            {{ isset($report->birth_date) ? \Carbon\Carbon::parse($report->birth_date)->format('d F Y') : \Carbon\Carbon::parse($report->created_at)->format('d F Y') }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>Death Time</label>
                                                                                                        <div class="line-data">
                                                                                                            {{ isset($report->birth_date) ? \Carbon\Carbon::parse($report->birth_date)->format('h:i A') : \Carbon\Carbon::parse($report->created_at)->format('h:i A') }}
                                                                                                        </div>
                                                                                                    </div>

                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Guardian Name</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->patient->guardian_name ?? '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>Relation</label>
                                                                                                        <div class="line-data">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <label>Address</label>
                                                                                                <div class="line-data">
                                                                                                    {{$report->patient->address ?? '-' }}
                                                                                                </div>
                                                                                                <div class="col-12 mt-3 mb-2">
                                                                                                    <label class="fw-bold mb-2">Cause of
                                                                                                        Death</label>

                                                                                                    <div class="table-responsive">
                                                                                                        <table class="table table-bordered">
                                                                                                            <thead>
                                                                                                                <tr>
                                                                                                                    <th width="30%">Type
                                                                                                                    </th>
                                                                                                                    <th>Details</th>
                                                                                                                    <th>ICD 10 Code</th>
                                                                                                                </tr>
                                                                                                            </thead>
                                                                                                            <tbody>
                                                                                                                <tr>
                                                                                                                    <td><strong>Immediate
                                                                                                                            Cause</strong>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        {{ $report->due_to_a }}
                                                                                                                    </td>
                                                                                                                    <td></td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><strong>Antecedent
                                                                                                                            Cause</strong>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        {{ $report->due_to_b }}
                                                                                                                    </td>
                                                                                                                    <td></td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><strong>Underlying
                                                                                                                            Cause</strong>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        {{ $report->due_to_c }}
                                                                                                                    </td>
                                                                                                                    <td></td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><strong>Manner Of
                                                                                                                            Death</strong>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        {{ $report->manner_of_death }}
                                                                                                                    </td>
                                                                                                                    <td></td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><strong>How did the
                                                                                                                            injury
                                                                                                                            occured?</strong>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        {{  '-' }}
                                                                                                                    </td>
                                                                                                                    <td></td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><strong>If deasesed
                                                                                                                            was a female,
                                                                                                                            was pregnancy
                                                                                                                            associated with
                                                                                                                            it?</strong>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        {{  '-' }}
                                                                                                                    </td>
                                                                                                                    <td></td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><strong>If Yes,was
                                                                                                                            there a
                                                                                                                            delivery?</strong>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        {{  '-' }}
                                                                                                                    </td>
                                                                                                                    <td></td>
                                                                                                                </tr>

                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </div>

                                                                                                    <div class="row mt-5">
                                                                                                        <div class="col-6 text-start">
                                                                                                            <div
                                                                                                                style="border-top: 1px solid #000; width: 250px; margin-top: 60px;">
                                                                                                                <p class="mb-0 mt-2">Date of
                                                                                                                    Verification</p>
                                                                                                                <small>See Reverse For
                                                                                                                    Instruction</small>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <div class="col-6 text-end">
                                                                                                            <div
                                                                                                                style="border-top: 1px solid #000; width: 250px; margin-top: 60px; float: right;">
                                                                                                                <p class="mb-0 mt-2">
                                                                                                                    Authorized Signature</p>
                                                                                                                <small>Full
                                                                                                                    Signature,Registration
                                                                                                                    No. of Medical
                                                                                                                    Attendant</small>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                </div>


                                                                                                <div class="cut_list"></div>

                                                                                                <div class="heading">
                                                                                                    <h3>Death Certificate</h3>
                                                                                                </div>


                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Name of Hospital :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->hospital->name ?? '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>(Patient Reg No.) :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->ipd_details->ipd_no ?? '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>Date :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{ $report->death_date ? \Carbon\Carbon::parse($report->death_date)->format('d F Y') : '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Address :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->hospital->address ?? '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Certified that Shri/Smt/Kum
                                                                                                            :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{ $report->patient_name }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>S/W/D of Shri/Smt:</label>
                                                                                                        <div class="line-data">
                                                                                                            {{ $report->guardian_name }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Sex :</label>
                                                                                                        {{-- <div
                                                                                                            class="line-data d-flex gap-2 align-items-center">
                                                                                                            M
                                                                                                            <input type="checkbox" name=""
                                                                                                                id="">
                                                                                                            /
                                                                                                            F
                                                                                                            <input type="checkbox" name=""
                                                                                                                id="">
                                                                                                            /
                                                                                                            Others
                                                                                                            <input type="checkbox" name=""
                                                                                                                id="">
                                                                                                        </div> --}}
                                                                                                        <div class="line-data">
                                                                                                            {{$report->patient->gender ?? '-' }}
                                                                                                        </div>


                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>Religion :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->patient->religion ?? '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>Occupation :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->patient->occupation ?? '-' }}

                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Guardian Name :</label>
                                                                                                        <div class="line-data">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>Relation :</label>
                                                                                                        <div class="line-data">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Address :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->patient->address ?? '-' }}

                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Under Doctor:</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->doctor_name ?? '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Admitted on this hospital
                                                                                                            :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->patient->created_at ? \Carbon\Carbon::parse($report->patient->created_at)->format('d F Y') : '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>Time :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->patient->created_at ? \Carbon\Carbon::parse($report->patient->created_at)->format('h:i A') : '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>hours :</label>
                                                                                                        <div class="line-data">

                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Expired on :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{ $report->death_date ? \Carbon\Carbon::parse($report->death_date)->format('d F Y') : '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>Time :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{ $report->death_date ? \Carbon\Carbon::parse($report->death_date)->format('h:i A') : '-' }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>hours :</label>
                                                                                                        <div class="line-data">

                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Due to (a) :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->due_to_a ?? '-' }}

                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>Due to (b) :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->due_to_b ?? '-' }}

                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col">
                                                                                                        <label>Due to (c) :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{$report->due_to_c ?? '-' }}

                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Full Signature :</label>
                                                                                                        <div class="line-data">

                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Full Name (IN BLOCK LETTER)
                                                                                                            :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{ strtoupper($report->patient->patient_name ?? '-') }}

                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col">
                                                                                                        <label>Medical Registration No.
                                                                                                            :</label>
                                                                                                        <div class="line-data">
                                                                                                            {{ $report->doctor->registration_no ?? '-' }}


                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>




                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <script
                                                                                        src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
                                                                                    <script
                                                                                        src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
                                                                                    <script>
                                                                                        function downloadCertificateAsPDF(contentId) {
                                                                                            var element = document.getElementById(contentId).querySelector('.certificate');
                                                                                            var opt = {
                                                                                                margin: [0, 0], // No margin
                                                                                                filename: 'birth_certificate.pdf',
                                                                                                image: { type: 'jpeg', quality: 1 },
                                                                                                html2canvas: { scale: 1.2, useCORS: true },
                                                                                                jsPDF: { unit: 'pt', format: [element.offsetWidth, element.offsetHeight], orientation: 'portrait' }
                                                                                            };
                                                                                            html2pdf().set(opt).from(element).save();
                                                                                        }
                                                                                        function printCertificate(contentId) {
                                                                                            var printContents = document.getElementById(contentId).innerHTML;
                                                                                            var originalContents = document.body.innerHTML;
                                                                                            document.body.innerHTML = printContents;
                                                                                            window.print();
                                                                                            document.body.innerHTML = originalContents;
                                                                                            location.reload();
                                                                                        }

                                                                                        function downloadCertificateAsImage(contentId) {
                                                                                            var element = document.getElementById(contentId).querySelector('.certificate');
                                                                                            html2canvas(element).then(function (canvas) {
                                                                                                var link = document.createElement('a');
                                                                                                link.download = 'birth_certificate.png';
                                                                                                link.href = canvas.toDataURL();
                                                                                                link.click();
                                                                                            });
                                                                                        }
                                                                                    </script>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                    </div>
                                                    </td>
                                                    </tr>
                                                @endforeach

                                    </tbody>
                                    </table>
                                </div>
                                {{-- Pagination Links --}}
                                <div class="mt-3" id="pagination-wrapper">
                                    @php
                                        $currentPage = $deathReports->currentPage();
                                        $lastPage = $deathReports->lastPage();
                                    @endphp

                                    @if ($deathReports->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                        <a href="{{ $deathReports->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    @endif

                                    @for ($page = 1; $page <= $lastPage; $page++)
                                        @if ($page == $currentPage)
                                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                        @else
                                            <a href="{{ $deathReports->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                        @endif
                                    @endfor

                                    @if ($deathReports->hasMorePages())
                                        <a href="{{ $deathReports->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm">Next »</a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Table end -->
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>



    <x-modals.birth-modal type="add" id="createModal" title="Add Death Record" action="{{ route('death.create') }}"
        :fields="[
            [
                'name' => 'case_reference_id',
                'label' => 'Case ID',
                'type' => 'text',
                'required' => false,

            ],
            [
                'name' => 'patient_id',
                'label' => 'Patient ID',
                'type' => 'number',  // integer input
                'required' => true,
                'size' => '3',

            ],
            [
                'name' => 'patient_name',
                'label' => 'Patient Name',
                'type' => 'text',
                'readonly' => true,
            ],
            ['name' => 'doctor_name', 'label' => 'Doctors', 'type' => 'select', 'required' => true, 'options' => $doctors->pluck('name', 'name')->toArray(), 'size' => '5'],
            ['name' => 'death_date', 'label' => 'Death Date', 'type' => 'date', 'required' => true, 'size' => '6'],

            ['name' => 'guardian_name', 'label' => 'Guardian Name ', 'type' => 'text', 'required' => true, 'size' => '12'],
            ['name' => 'due_to_a', 'label' => 'Due to (a)', 'type' => 'text', 'required' => false, 'size' => '5'],
            ['name' => 'due_to_b', 'label' => 'Due to (b)', 'type' => 'text', 'required' => false, 'size' => '5'],
            ['name' => 'due_to_c', 'label' => 'Due to (c)', 'type' => 'text', 'required' => false, 'size' => '5'],

            [
                'name' => 'attachment_name',
                'label' => 'Report',
                'type' => 'text',
                'required' => false,
                'size' => '6',
            ],
            ['name' => 'attachment', 'label' => 'Attachment', 'type' => 'file', 'required' => false, 'size' => '6',],
            [
                'name' => 'manner_of_death',
                'label' => 'Manner Of Death',
                'type' => 'select',
                'required' => false,
                'size' => '5',
                'options' => [
                    'Accident' => 'Accident',
                    'Natural Death' => 'Natural Death',
                    'Suicide' => 'Suicide',
                    'Homicide' => 'Homicide',
                    'Pending Investigation' => 'Pending Investigation',
                ],
            ],

        ]"
        :columns="3" />
    <x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Death Name"
        action="{{ url('/death/update') }}" :fields="[
            ['name' => 'id', 'type' => 'hidden', 'required' => true],

            [
                'name' => 'case_id',
                'label' => 'Case ID',
                'type' => 'text',
                'required' => true,

            ],
            [
                'name' => 'patient_id',
                'label' => 'Patient ID',
                'type' => 'number',  // integer input
                'required' => true,
                'size' => '3',

            ],
            [
                'name' => 'patient_name',
                'label' => 'Patient Name',
                'type' => 'text',
                'readonly' => true,
            ],
            ['name' => 'doctor_name', 'label' => 'Doctors', 'type' => 'select', 'required' => true, 'options' => $doctors->pluck('name', 'name')->toArray(), 'size' => '5'],

            ['name' => 'death_date', 'label' => 'Death Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'guardian_name', 'label' => 'Guardian Name ', 'type' => 'text', 'required' => true, 'size' => '12'],
            [
                'name' => 'report',
                'label' => 'Report',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
            ['name' => 'attachment', 'label' => 'Attachment', 'type' => 'file', 'required' => false, 'size' => '6',],
            ['name' => 'due_to_a', 'label' => 'Due to (a)', 'type' => 'text', 'required' => false, 'size' => '5'],
            ['name' => 'due_to_b', 'label' => 'Due to (b)', 'type' => 'text', 'required' => false, 'size' => '5'],
            ['name' => 'due_to_c', 'label' => 'Due to (c)', 'type' => 'text', 'required' => false, 'size' => '5'],


            [
                'name' => 'manner_of_death',
                'label' => 'Manner Of Death',
                'type' => 'select',
                'required' => false,
                'size' => '5',
                'options' => [
                    'Accident' => 'Accident',
                    'Natural Death' => 'Natural Death',
                    'Suicide' => 'Suicide',
                    'Homicide' => 'Homicide',
                    'Pending Investigation' => 'Pending Investigation',
                ],
            ],
        ]" :columns="3" />

    <script>
        const patientIdField = document.getElementById("patient_id")
        patientIdField.addEventListener("input", function () {
            const patientId = this.value.trim();
            const patientNameField = document.getElementById("patient_name");

            // Clear patient name if input is empty
            if (!patientId) {
                patientNameField.value = '';
                return;
            }

            // Call API
            const baseUrl = "{{ route('death.patient', ['id' => 'ID']) }}";
            const finalUrl = baseUrl.replace('ID', patientId);
            fetch(finalUrl)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        patientNameField.value = data.patient.patient_name; // Fill patient_name
                    } else {
                        patientNameField.value = ''; // Clear if not found
                        console.log(data.message);
                    }
                })
                .catch(err => {
                    patientNameField.value = '';
                    console.error("API Error:", err);
                });
        });



    </script>



@endsection()