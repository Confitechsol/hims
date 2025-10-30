{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Vital List</h5>
                </div>

                <div class="card-body">


                    {{-- Hospital Name & Code --}}
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                        <div class="input-icon-start position-relative me-2">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" class="form-control shadow-sm" placeholder="Search">

                                        </div>
                                        <div class="page_btn d-flex">
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                    data-bs-toggle="modal" data-bs-target="#add_vital"><i
                                                        class="ti ti-plus me-1"></i>Add Vital</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_vital" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Vital

                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('vital.store') }}" method="POST">
                                                                @csrf
                                                                <div class="row gy-3 mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-12">
                                                                        <label for="vital_name" class="form-label">Vital
                                                                            Name
                                                                            <span class="text-danger">*</span></label>
                                                                        <input type="text" name="vital_name" id="vital_name"
                                                                            class="form-control" required />
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <p><strong>Reference Range</strong> (IF vital is
                                                                            having single value rather than range then enter
                                                                            only from textbox value)</p>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <input type="text" name="range_from" id="from"
                                                                            class="form-control" placeholder="From" />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" name="range_to" id="to"
                                                                            class="form-control" placeholder="To" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="vital_name" class="form-label">Unit</label>
                                                                        <input type="text" name="unit" id="unit"
                                                                            class="form-control" />
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
                                        </div>

                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Reference Range</th>
                                                    <th>Unit</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($vitals as $vital)
                                                    <tr>
                                                        <td><h6 class="mb-0 fs-14 fw-semibold">{{ $vital->name }}</h6></td>
                                                        <td>
                                                            {{ $vital->reference_range }}
                                                           
                                                        </td>
                                                        <td>{{ $vital->unit }}</td>
                                                        <td>
                                                            <a href="javascript:void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                            onclick="openVitalEditModal(this)"
                                                            data-id="{{ $vital->id }}"
                                                            data-vital_name="{{ $vital->name }}"
                                                            data-from="{{ $vital->range_from }}"
                                                            data-to="{{ $vital->range_to }}"
                                                            data-unit="{{ $vital->unit }}">
                                                                <i class="ti ti-pencil"></i>
                                                            </a>

                                                            <a href="javascript:void(0);"
                                                            onclick="deleteVital({{ $vital->id }})"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                <i class="ti ti-trash"></i>
                                                            </a>
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
    </div>
    <div class="modal fade" id="edit_vital_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editVitalForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header rounded-0"
                     style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="modal-title">Edit Vital</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_vital_id">

                    <div class="mb-3">
                        <label for="edit_vital_name" class="form-label">Vital Name</label>
                        <input type="text" name="vital_name" id="edit_vital_name" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" name="range_from" id="edit_from" class="form-control" placeholder="From">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" name="range_to" id="edit_to" class="form-control" placeholder="To">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_unit" class="form-label">Unit</label>
                        <input type="text" name="unit" id="edit_unit" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openVitalEditModal(button) {
    let id = button.getAttribute('data-id');
    let name = button.getAttribute('data-vital_name');
    let from = button.getAttribute('data-from');
    let to = button.getAttribute('data-to');
    let unit = button.getAttribute('data-unit');

    document.getElementById('edit_vital_id').value = id;
    document.getElementById('edit_vital_name').value = name;
    document.getElementById('edit_from').value = from;
    document.getElementById('edit_to').value = to;
    document.getElementById('edit_unit').value = unit;

    let form = document.getElementById('editVitalForm');
    form.action = '{{ url('vital/update') }}/' + id; // update route

    new bootstrap.Modal(document.getElementById('edit_vital_modal')).show();
}

function deleteVital(id) {
    if (confirm('Are you sure you want to delete this vital?')) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url('vital/destroy') }}/' + id;

        let token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = '{{ csrf_token() }}';

        let method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(token);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}

</script>
@endsection