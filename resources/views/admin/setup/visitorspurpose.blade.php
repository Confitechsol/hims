{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

        <div class="row justify-content-center">
            {{-- Settings Form --}}
            <div class="col-md-11">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Purpose List</h5>
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
                                                <div class="text-end d-flex">
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                        data-bs-toggle="modal" data-bs-target="#purposeModal"><i
                                                            class="ti ti-plus me-1"></i>Create purpose </a>
                                                </div>
                                                
                                                <!-- purpose  Modal -->
                                                <div class="modal fade" id="purposeModal" tabindex="-1" aria-labelledby="purposeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header rounded-0"
                                                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                <h5 class="modal-title" id="purposeModalLabel">Create Purpose</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="purposeForm" method="POST" action="{{ route('purposes.store') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="_method" id="purposeFormMethod" value="POST">

                                                                    <div id="purposeFieldsWrapper">
                                                                        <div class="purpose-item mb-3 d-flex gap-2">
                                                                            <div class="flex-grow-1">
                                                                                <label class="form-label">Purpose Name</label>
                                                                                <input name="purposes[0][name]" class="form-control" required />
                                                                            </div>
                                                                            <div class="flex-grow-1">
                                                                                <label class="form-label">Description</label>
                                                                                <input name="purposes[0][description]" class="form-control" required />
                                                                            </div>
                                                                            <button type="button" class="btn btn-danger btn-remove-purpose align-self-end">Remove</button>
                                                                        </div>
                                                                    </div>

                                                                    <button type="button" class="btn btn-secondary mb-3" id="addPurposeBtn">Add Another Purpose</button>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Save Purposes</button>
                                                            </div>
                                                                </form>
                                                        </div>
                                                    </div>
                                                </div>




                                            </div>

                                            <div class="table-responsive">
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Purposes </th>
                                                            <th>Description</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                         @foreach($purposes as $index => $purpose)
                                                            <tr>
                                                                <th scope="row">{{ $index + 1 }}</th>
                                                                <td>
                                                                    <h6 class="mb-0 fs-14 fw-semibold">{{ $purpose->visitors_purpose }}</h6>
                                                                </td>
                                                                <td>{{ $purpose->description }}</td>
                                                                <td>
                                                                    
                                                                   
                                                                    <a href="javascript:void(0);" 
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill"
                                                                        data-purpose-id="{{ $purpose->id }}" 
                                                                        data-purpose-name="{{ $purpose->visitors_purpose }}" 
                                                                        data-purpose-description="{{ $purpose->description }}"
                                                                        onclick="openPurposeModal(this)">
                                                                        <i class="ti ti-pencil"></i>
                                                                    </a>
                                                                    <a href="javascript:void(0);" 
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Delete"
                                                                    onclick="if(confirm('Are you sure you want to delete this purpose ?')) { document.getElementById('delete-purpose -{{ $purpose ->id }}').submit(); }">
                                                                        <i class="ti ti-trash"></i>
                                                                    </a>

                                                                    <form id="delete-purpose -{{ $purpose ->id }}" 
                                                                        action="{{ route('purposes.destroy', $purpose ->id) }}" 
                                                                        method="POST" style="display: none;">
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
                            <!-- <hr> -->
                            <!-- <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-1"></i> Save Settings
                                </button>
                            </div> -->
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Purpose Modal -->
        <div class="modal fade" id="editPurposeModal" tabindex="-1" aria-labelledby="editPurposeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header rounded-0"
                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="modal-title" id="editPurposeModalLabel">Edit Purpose</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editPurposeForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Purpose Name</label>
                                <input type="text" name="name" id="editPurposeName" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" name="description" id="editPurposeDescription" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update Purpose</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS bundle (includes Popper) -->
        <script>
            let purposeIndex = 1;

            document.getElementById('addPurposeBtn').addEventListener('click', function () {
                const wrapper = document.getElementById('purposeFieldsWrapper');

                const newItem = document.createElement('div');
                newItem.classList.add('purpose-item', 'mb-3', 'd-flex', 'gap-2');
                newItem.innerHTML = `
                    <div class="flex-grow-1">
                        <label class="form-label">Purpose Name</label>
                        <input name="purposes[${purposeIndex}][name]" class="form-control" required />
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-label">Description</label>
                        <input name="purposes[${purposeIndex}][description]" class="form-control" required />
                    </div>
                    <button type="button" class="btn btn-danger btn-remove-purpose align-self-end">Remove</button>
                `;
                wrapper.appendChild(newItem);
                purposeIndex++;
            });

            document.getElementById('purposeFieldsWrapper').addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove-purpose')) {
                    e.target.closest('.purpose-item').remove();
                }
            });
        </script>
        <script>
            function openPurposeModal(el) {
                let id = el.getAttribute("data-purpose-id");
                let name = el.getAttribute("data-purpose-name");
                let description = el.getAttribute("data-purpose-description");

                // Fill modal inputs
                document.getElementById("editPurposeName").value = name;
                document.getElementById("editPurposeDescription").value = description;

                // Update form action dynamically
                let form = document.getElementById("editPurposeForm");
                form.action = "{{ url('visitorspurpose') }}/update/" + id; // route to update

                // Show modal
                let modal = new bootstrap.Modal(document.getElementById("editPurposeModal"));
                modal.show();
            }
        </script>


@endsection
