{{-- resources/views/admin/setup/gst_master.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <style>
        .form-select {
            padding: 0.5rem 0.75rem !important;
        }
    </style>

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>GST Master List</h5>
                </div>
                <div class="card-body">
                    {{-- Custom Action Buttons --}}
                    <div class="d-flex justify-content-between w-100 mb-3">
                        <div class="text-end d-flex">
                            <a href="javascript:void(0);" class="btn btn-primary text-white fs-13 btn-md" data-bs-toggle="modal"
                                data-bs-target="#createModal"><i class="ti ti-plus me-1"></i>Add GST Master</a>
                            <a href="javascript:void(0);" class="btn btn-success text-white fs-13 btn-md ms-2" data-bs-toggle="modal"
                                data-bs-target="#importModal"><i class="ti ti-upload me-1"></i>Import CSV</a>
                            <a href="{{ route('gst_master.download.sample') }}" class="btn btn-info text-white fs-13 btn-md ms-2">
                                <i class="ti ti-download me-1"></i>Download CSV Sample</a>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-info" onclick="exportToCSV('gst_master')">Export to CSV</button>
                        </div>
                    </div>
                    <div class="input-icon-start position-relative mb-3">
                        <span class="input-icon-addon">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" class="form-control shadow-sm" placeholder="Search" id="search-input">
                    </div>
                    {{-- GST Master Form --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if(session('error'))
                                        <div class="alert alert-danger">
                                            {{session('error')}}
                                        </div>
                                    @endif
                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            {{session('success')}}
                                        </div>
                                    @endif
                                    <!-- Modal -->
                                    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addGstMasterLabel">Add GST Master</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('gst_master.store')}}" method="POST">
                                                        @csrf
                                                        <div class="row gy-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Code <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="code" id="code"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Description <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea name="description" id="description"
                                                                    class="form-control" rows="3" required></textarea>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">GST Rate (%) <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" name="gst_rate" id="gst_rate"
                                                                    class="form-control" step="0.01" min="0" max="100" required>
                                                            </div>
                                                        </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Import CSV Modal -->
                                    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="importModalLabel">Import GST Master CSV</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('gst_master.import')}}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="csv_file" class="form-label">Select CSV File <span class="text-danger">*</span></label>
                                                            <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv,.xlsx,.xls" required>
                                                            <small class="form-text text-muted">Supported formats: CSV, XLSX, XLS</small>
                                                        </div>
                                                        <div class="alert alert-info">
                                                            <strong>CSV Format:</strong><br>
                                                            Columns: Code, Description, GST Rate (%)<br>
                                                            <a href="{{ route('gst_master.download.sample') }}" class="text-primary">Download Sample CSV</a>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Import</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table mb-0" id="gst_master">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Description</th>
                                                <th>GST Rate (%)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($gstMasters as $gstMaster)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{$gstMaster->code}}</h6>
                                                    </td>
                                                    <td>{{$gstMaster->description}}</td>
                                                    <td>{{number_format($gstMaster->gst_rate, 2)}}%</td>
                                                    <td>

                                                        <a href="javascript:void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#edit_gst_master_{{$gstMaster->id}}">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="edit_gst_master_{{$gstMaster->id}}"
                                                            tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header rounded-0"
                                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                        <h5 class="modal-title" id="editGstMasterLabel">Edit
                                                                            GST Master</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{route('gst_master.update')}}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input type="hidden" name="id"
                                                                                value="{{$gstMaster->id}}">
                                                                            <div class="row gy-3">
                                                                                <div class="col-md-12">
                                                                                    <label for="" class="form-label">Code
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="text" name="code" id="code"
                                                                                        class="form-control"
                                                                                        value="{{$gstMaster->code}}" required>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <label for="" class="form-label">Description
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <textarea name="description"
                                                                                        id="description" class="form-control"
                                                                                        rows="3"
                                                                                        required>{{$gstMaster->description}}</textarea>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <label for="" class="form-label">GST Rate (%)
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="number" name="gst_rate" id="gst_rate"
                                                                                        class="form-control" step="0.01" min="0" max="100"
                                                                                        value="{{$gstMaster->gst_rate}}" required>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Update</button>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Modal End -->
                                                        <form class="d-inline" id="delete-form-{{$gstMaster->id}}"
                                                            action="{{route('gst_master.destroy')}}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id"
                                                                value="{{$gstMaster->id}}">
                                                            <button type="button" onclick="confirmDeleteGst({{$gstMaster->id}}, '{{$gstMaster->code}}')"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"><i
                                                                    class="ti ti-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                </div>

            </div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<script>
    // Export to CSV (excluding Action column)
    function exportToCSV(tableId) {
        const table = document.getElementById(tableId);
        // Clone table to exclude Action column
        const clonedTable = table.cloneNode(true);
        const actionColumnIndex = clonedTable.rows[0].cells.length - 1; // Last column is Action
        
        // Remove Action column from all rows
        Array.from(clonedTable.rows).forEach(row => {
            if (row.cells.length > actionColumnIndex) {
                row.deleteCell(actionColumnIndex);
            }
        });
        
        const wb = XLSX.utils.table_to_book(clonedTable);
        const csv = XLSX.utils.sheet_to_csv(wb.Sheets[wb.SheetNames[0]]);
        const blob = new Blob([csv], { type: 'text/csv' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${tableId}_export.csv`;
        link.click();
    }

    // Professional Delete Confirmation with SweetAlert2
    function confirmDeleteGst(id, code) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete GST Master "${code}". This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            buttonsStyling: true,
            reverseButtons: true,
            customClass: {
                popup: 'swal2-popup-custom',
                confirmButton: 'swal2-confirm-custom',
                cancelButton: 'swal2-cancel-custom'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>

@endsection
