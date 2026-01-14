@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Birth List</h5>
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
                                   
                                    <x-table-actions.actions id="birth" name="Birth Record" doctors={{$doctors}} />
                                    <!-- Table start -->
                                    <div class="table-responsive table-nowrap">
                                        <table class="table" id="birth">
                                            <thead class="thead-light">
                                                <tr>			 	
                                                    <th>Reference No</th>
                                                    <th>Case ID</th>
                                                    <th>Child Name</th>
                                                    <th>Gender</th>
                                                    <th>Birth Date</th>
                                                    <th>Mother Name</th>
                                                    <th>Father Name</th>
                                                    <th>Action</th>
                                                    <th>Generated Birth Certificate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                             @foreach($birthReports as $report)
                                                    <tr>
                                                        <td>{{$report->id}}</td>
                                                        <td>{{$report->case_reference_id}}</td>
                                                        <td>{{$report->child_name}}</td>
                                                        <td>{{$report->gender}}</td>
                                                        <td>{{\Carbon\Carbon::parse($report->created_at)->format('d/m/Y h:i A') }}</td>
                                                        <td>{{$report->mother_name }}</td>
                                                        <td>{{$report->father_name}}</td>
                                                        
                                                        {{-- <td>
                                                            <div class="d-flex">
                                                                <button
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                                    data-id="">
                                                                    <i class="ti ti-pencil"></i>
                                                                </button>
                                                                <form method="POST" action="">

                                                                    <input type="hidden" name="id" value="">
                                                                    <button type="submit"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr> --}}
                                                    <td>
    <div class="d-flex">
        <button
            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
            data-id="{{ $report->id }}"
            data-child_name="{{ $report->child_name }}"
            data-gender="{{ $report->gender }}"
            data-weight="{{ $report->weight ?? '' }}"
            data-birth_date="{{ isset($report->birth_date) ? \Carbon\Carbon::parse($report->birth_date)->format('Y-m-d') : \Carbon\Carbon::parse($report->created_at)->format('Y-m-d') }}"
            data-contact_person_phone="{{ $report->contact ?? '' }}"
            data-address="{{ $report->address ?? '' }}"
            data-caseId="{{ $report->case_reference_id ?? '' }}"
            data-mother_name="{{ $report->mother_name ?? '' }}"
            data-contact_person_name="{{ $report->contact_person_name ?? '' }}"
            data-father_name="{{ $report->father_name ?? '' }}"
            data-report="{{ $report->birth_report }}"
            data-icd_code="{{ $report->icd_code ?? '' }}">
            <i class="ti ti-pencil"></i>
        </button>

        <form action="{{ route('birth.delete', $report->id) }}" 
              method="POST" 
              style="display:inline;">
            @csrf
            @method('DELETE')

            <button type="submit" 
                    onclick="return confirm('Are you sure you want to delete this record?')" 
                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                <i class="ti ti-trash"></i>
            </button>
        </form>

    </div>
</td>


                                                                                                        <td>
                                                                                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#birthCertModal{{ $report->id }}">
                                                                                                                        Generated Birth Certificate
                                                                                                                </button>

                                                                                                                <!-- Modal for Certificate Design (Rendered Directly) -->
                                                                                                                <div class="modal fade" id="birthCertModal{{ $report->id }}" tabindex="-1" aria-labelledby="birthCertModalLabel{{ $report->id }}" aria-hidden="true">
                                                                                                                    <div class="modal-dialog modal-fullscreen">
                                                                                                                        <div class="modal-content">
                                                                                                                            <div class="modal-header">
                                                                                                                                <h5 class="modal-title" id="birthCertModalLabel{{ $report->id }}">Birth Certificate</h5>
                                                                                                                                <div class="d-flex gap-2">
                                                                                                                                    <button class="btn btn-outline-primary btn-sm" onclick="printCertificate('certificateContent{{ $report->id }}')"><i class="fa fa-print"></i> Print</button>
                                                                                                                                    <button class="btn btn-outline-success btn-sm" onclick="downloadCertificateAsImage('certificateContent{{ $report->id }}')"><i class="fa fa-download"></i> Download Image</button>
                                                                                                                                    <button class="btn btn-outline-danger btn-sm" onclick="downloadCertificateAsPDF('certificateContent{{ $report->id }}')"><i class="fa fa-file-pdf"></i> Download PDF</button>
                                                                                                                                </div>
                                                                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                                                                                                </style>
                                                                                                                                <div class="certificate">
                                                                                                                                    <div class="header">
                                                                                                                                        <div class="logo-box">
                                                                                                                                            <img src="{{ asset('assets/img/logo.png') }}" alt="COGNAIHEALTH">
                                                                                                                                        </div>
                                                                                                                                        <h1>Birth Certificate</h1>
                                                                                                                                    </div>
                                                                                                                                    <h2>Child Information</h2>
                                                                                                                                    <div class="row">
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Full Name</label>
                                                                                                                                            <div class="line-data">{{ $report->child_name }}</div>
                                                                                                                                        </div>
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Sex</label>
                                                                                                                                            <div class="line-data">{{ $report->gender }}</div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <div class="row">
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Date of Birth</label>
                                                                                                                                            <div class="line-data">{{ isset($report->birth_date) ? \Carbon\Carbon::parse($report->birth_date)->format('d F Y') : \Carbon\Carbon::parse($report->created_at)->format('d F Y') }}</div>
                                                                                                                                        </div>
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Time of Birth</label>
                                                                                                                                            <div class="line-data">{{ isset($report->birth_date) ? \Carbon\Carbon::parse($report->birth_date)->format('h:i A') : \Carbon\Carbon::parse($report->created_at)->format('h:i A') }}</div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <label>Place of Birth</label>
                                                                                                                                    <div class="line-data">{{ $report->address ?? 'Sunrise Multispeciality Hospital, Kolkata' }}</div>
                                                                                                                                    <div class="row">
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Birth Weight</label>
                                                                                                                                            <div class="line-data">{{ $report->weight }}</div>
                                                                                                                                        </div>
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Birth Length</label>
                                                                                                                                            <div class="line-data">49 cm</div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <div class="row">
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Apgar Score (1 min / 5 min)</label>
                                                                                                                                            <div class="line-data">8 / 9</div>
                                                                                                                                        </div>
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Gestational Age (Weeks)</label>
                                                                                                                                            <div class="line-data">38 weeks</div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <h2>ICD-10 Coding</h2>
                                                                                                                                    <label>Live Birth Code (Z38 Series)</label>
                                                                                                                                    <div class="line-data">Z38.0 – Single live birth in hospital</div>
                                                                                                                                    <label>Maternal Conditions (O00 – O99)</label>
                                                                                                                                    <div class="line-data">O80 – Full-term uncomplicated delivery</div>
                                                                                                                                    <label>Congenital Anomalies (Q00 – Q99)</label>
                                                                                                                                    <div class="line-data">None Reported</div>
                                                                                                                                    <h2>Mother's Details</h2>
                                                                                                                                    <div class="row">
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Full Name</label>
                                                                                                                                            <div class="line-data">{{ $report->mother_name }}</div>
                                                                                                                                        </div>
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Age</label>
                                                                                                                                            <div class="line-data">--</div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <label>Address</label>
                                                                                                                                    <div class="multi-data">{{ $report->address }}</div>
                                                                                                                                    <div class="row">
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Mobile Number</label>
                                                                                                                                            <div class="line-data">{{ $report->contact ?? '--' }}</div>
                                                                                                                                        </div>
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Blood Group</label>
                                                                                                                                            <div class="line-data">B+</div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <h2>Father's Details</h2>
                                                                                                                                    <div class="row">
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Full Name</label>
                                                                                                                                            <div class="line-data">{{ $report->father_name }}</div>
                                                                                                                                        </div>
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Age</label>
                                                                                                                                            <div class="line-data">{{ $report->father_age }}</div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <label>Address</label>
                                                                                                                                    <div class="multi-data">{{ $report->address }}</div>
                                                                                                                                    <div class="row">
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Mobile Number</label>
                                                                                                                                            <div class="line-data">{{ $report->contact ?? '--' }}</div>
                                                                                                                                        </div>
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Blood Group</label>
                                                                                                                                            <div class="line-data">{{ $report->father_blood_group }}</div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <h2>Informant Details</h2>
                                                                                                                                    <div class="row">
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Name of Informant</label>
                                                                                                                                            <div class="line-data">{{ $report->father_name }}</div>
                                                                                                                                        </div>
                                                                                                                                        <div class="col">
                                                                                                                                            <label>Relation to Child</label>
                                                                                                                                            <div class="line-data">Father</div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <label>Date of Reporting</label>
                                                                                                                                    <div class="line-data">{{ isset($report->created_at) ? \Carbon\Carbon::parse($report->created_at)->format('d F Y') : '--' }}</div>
                                                                                                                                    <h2>Authentication</h2>
                                                                                                                                    <div class="seal-section">
                                                                                                                                        <div class="sign-box">{{ $report->doctor->name ?? 'N/A' }}<br></div>
                                                                                                                                        <div class="seal-box"> {{ $report->hospital->name ?? ($hospital->name ?? 'N/A') }}</div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
                                                                                                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
                                                                                                                        <script>
                                                                                                                                                                                                                                                function downloadCertificateAsPDF(contentId) {
                                                                                                                                                                                                                                                    var element = document.getElementById(contentId).querySelector('.certificate');
                                                                                                                                                                                                                                                    var opt = {
                                                                                                                                                                                                                                                        margin:       [0, 0], // No margin
                                                                                                                                                                                                                                                        filename:     'birth_certificate.pdf',
                                                                                                                                                                                                                                                        image:        { type: 'jpeg', quality: 1 },
                                                                                                                                                                                                                                                        html2canvas:  { scale: 1.2, useCORS: true },
                                                                                                                                                                                                                                                        jsPDF:        { unit: 'pt', format: [element.offsetWidth, element.offsetHeight], orientation: 'portrait' }
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
                                                                                                                            html2canvas(element).then(function(canvas) {
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
                                                                                                @endforeach

<script>
function loadBirthCertDesign(birthId) {
        var modalBody = document.getElementById('birthCertContent' + birthId);
        modalBody.innerHTML = '<div class="text-center py-5"><span class="spinner-border"></span> Loading...</div>';
        fetch('/hims/public/certificate?birth_id=' + birthId + '&modal=1')
                .then(response => response.text())
                .then(html => {
                        // Try to extract only the certificate HTML if needed
                        var parser = new DOMParser();
                        var doc = parser.parseFromString(html, 'text/html');
                        var cert = doc.querySelector('.certificate');
                        if(cert) {
                                modalBody.innerHTML = '';
                                modalBody.appendChild(cert);
                        } else {
                                modalBody.innerHTML = html;
                        }
                });
}
</script>

                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- Pagination Links --}}
                                    <div class="mt-3" id="pagination-wrapper">
                                      @php
                                          $currentPage = $birthReports->currentPage();
                                          $lastPage = $birthReports->lastPage();
                                    @endphp

                                    @if ($birthReports->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                    <a href="{{ $birthReports->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                     class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    @endif

                                    @for ($page = 1; $page <= $lastPage; $page++)
                                    @if ($page == $currentPage)
                                    <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                    @else
                                   <a href="{{ $birthReports->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                   class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                    @endif
                                    @endfor

                                   @if ($birthReports->hasMorePages())
                                   <a href="{{ $birthReports->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
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
    <x-modals.birth-modal type="add" id="createModal" class="modal-fullscreen" title="Add Birth Record" action="{{ route('birth.create') }}"
        :fields="[
            [
                'name' => 'child_name',
                'label' => 'Child Name',
                'type' => 'text',
                'required' => true,
                'size' => '8',
            ],
            [ 'name' => 'gender', 'label' => 'Gender', 'type' => 'select', 'required' => true, 'options' => [     'Male' => 'Male',   'Female' => 'Female' ], 'size' => '3'],

            ['name' => 'weight', 'label' => 'Weight', 'type' => 'text', 'required' => true, 'size' => '4'],
            
            ['name' => 'baby_image', 'label' => 'Child Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            ['name' => 'birth_date', 'label' => 'Birth Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'contact_person_phone', 'label' => 'Phone','required' => true, 'type' => 'text',  'size' => '6'],
            ['name' => 'address', 'label' => 'Address', 'type' => 'text',  'size' => '12'],
            ['name' => 'caseId', 'label' => 'Case Id', 'type' => 'text',  'size' => '6'],
            ['name' => 'patient_id', 'label' => 'Patient Id', 'type' => 'text',  'size' => '6'],
           [
                'name' => 'mother_name',
                'label' => 'Mother Name ',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            {{-- [
                'name' => 'mother_address',
                'label' => 'Mother Address',
                'type' => 'text',
                'required' => false,
                'size' => '5',
            ], --}}
            ['name' => 'mother_image', 'label' => 'Mother Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            
            [
                'name' => 'father_name',
                'label' => 'Father Name ',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            [
                'name' => 'father_address',
                'label' => 'Father Address',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],

            [
                'name' => 'father_blood_group',
                'label' => 'Father Blood Group',
                'type' => 'text',
                'required' => false,
                'size' => '5',
            ],
             
            [
                'name' => 'father_age',
                'label' => 'Father Age',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],

            ['name' => 'father_image', 'label' => 'Father Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            
            [
                'name' => 'report',
                'label' => 'Report',
                'required' => true,
                'type' => 'text',
                'size' => '5',
            ],
           ['name' => 'report_image', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '6',],

             [
                'name' => 'icd_code',
                'label' => 'ICD Code',
                'required' => true,
                'type' => 'text',
                'size' => '5',
            ],

         [ 'name' => 'doctor', 'label' => 'Doctors', 'type' => 'select', 'required' => true, 'options' => $doctors->pluck('name','id')->toArray(),  'size' => '5'],
     
       






           
        ]" :columns="4" />
    <x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Birth"
        action="{{ url('/birth/update') }}" :fields="[
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'child_name',
                'label' => 'Child Name',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            [ 'name' => 'gender', 'label' => 'Gender', 'type' => 'select', 'required' => true, 'options' => [     'Male' => 'Male',   'Female' => 'Female' ], 'size' => '3'],

            ['name' => 'weight', 'label' => 'Weight', 'type' => 'text', 'required' => true, 'size' => '4'],
            
            ['name' => 'baby_image', 'label' => 'Child Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            ['name' => 'birth_date', 'label' => 'Birth Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'contact_person_phone', 'label' => 'Phone', 'type' => 'text',  'size' => '6'],
            ['name' => 'address', 'label' => 'Address', 'type' => 'text',  'size' => '12'],
            ['name' => 'caseId', 'label' => 'Case Id', 'type' => 'text',  'size' => '6'],
           [
                'name' => 'mother_name',
                'label' => 'Mother Name ',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'mother_image', 'label' => 'Mother Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            
            [
                'name' => 'father_name',
                'label' => 'Father Name ',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'father_image', 'label' => 'Father Photo', 'type' => 'file', 'required' => false, 'size' => '6',],
            
            [
                'name' => 'report',
                'label' => 'Report',
                'type' => 'text',
                'size' => '5',
            ],

            [
                'name' => 'icd_code',
                'label' => 'ICD Code',
                'required' => true,
                'type' => 'text',
                'size' => '5',
            ],
           
        ]" :columns="3" />

   

@endsection()