{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Symptoms Head List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_symptom_head"><i
                                                        class="ti ti-plus me-1"></i>Add Symptoms Head</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_symptom_head" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Symptoms
                                                                Head
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('symptoms-head.store') }}" method="POST">
                                                                @csrf
                                                                <div class="row gy-3 mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-12">
                                                                        <label for="symptom_head"
                                                                            class="form-label">Symptoms Head <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="symptoms_title"
                                                                            id="symptom_head" class="form-control"
                                                                            required />
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <label for="type" class="form-label">Symptoms Type
                                                                            <span class="text-danger">*</span></label>
                                                                        <select name="type" id="type" onchange=""
                                                                            class="form-select">
                                                                            <option value="">Select</option>
                                                                            @foreach($classifications as $classification)
                                                                                <option value="{{ $classification->id }}">
                                                                                    {{ $classification->symptoms_type }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="description"
                                                                            class="form-label">Description</label>
                                                                        <textarea name="description" id="description"
                                                                            class="form-control"></textarea>
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
                                                    <th>Symptoms Head</th>
                                                    <th>Symptoms Type</th>
                                                    <th>Symptoms Description</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                  @foreach($symptoms as $symptom)
        <tr>
            <td>
                <h6 class="mb-0 fs-14 fw-semibold">{{ $symptom->symptoms_title }}</h6>
            </td>
            <td>{{ $symptom->classification->symptoms_type }}</td>
            <td>{{ $symptom->description }}</td>
            <td>
                <!-- Edit button -->
                <a href="javascript:void(0);"
                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                    onclick="openSymptomHeadModal(this)"
                    data-symptom-id="{{ $symptom->id }}"
                    data-symptom-title="{{ $symptom->symptoms_title }}"
                    data-symptom-type="{{ $symptom->type }}"
                    data-symptom-description="{{ $symptom->description }}">
                    <i class="ti ti-pencil"></i>
                </a>

                <a href="javascript:void(0);"
                    onclick="deleteSymptomHead({{ $symptom->id }})"
                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                    <i class="ti ti-trash"></i>
                </a>
                <form id="deleteSymptomHeadForm" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
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
    </div>
    <div class="modal fade" id="editSymptomHeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Symptom Head</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSymptomHeadForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label class="form-label">Symptoms Head</label>
                        <input type="text" class="form-control" name="symptoms_title" id="editSymptomTitle" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Symptoms Type</label>
                        <select class="form-select" name="type" id="editSymptomType" required>
                            <option value="">Select</option>
                            @foreach($classifications as $classification)
                                <option value="{{ $classification->id }}">
                                    {{ $classification->symptoms_type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="editSymptomDescription"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function openSymptomHeadModal(el) 
    {
        let id = el.getAttribute("data-symptom-id");
        let title = el.getAttribute("data-symptom-title");
        let type = el.getAttribute("data-symptom-type");
        let description = el.getAttribute("data-symptom-description");

        // Fill modal fields
        document.getElementById("editSymptomTitle").value = title;
        document.getElementById("editSymptomType").value = type;
        document.getElementById("editSymptomDescription").value = description;

        // Update form action
        let form = document.getElementById("editSymptomHeadForm");
        form.action = "{{ url('symptoms-head/update') }}/" + id;

        // Show modal
        let modal = new bootstrap.Modal(document.getElementById("editSymptomHeadModal"));
        modal.show();
    }
</script>
<script>
    function deleteSymptomHead(id) 
    {
        if (confirm("Are you sure you want to delete this symptom head?")) {
            let form = document.getElementById("deleteSymptomHeadForm");
            form.action = "{{ url('symptoms-head/destroy') }}/" + id;
            form.submit();
        }
    }
</script>

@endsection