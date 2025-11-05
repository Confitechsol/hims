@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    {{-- Settings Form --}}
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Source List</h5>
            </div>

            <div class="card-body">
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
                                            data-bs-toggle="modal" data-bs-target="#sourceModal"><i
                                                class="ti ti-plus me-1"></i>Create Source</a>
                                    </div>

                                    <!-- Source Modal -->
                                    <div class="modal fade" id="sourceModal" tabindex="-1" aria-labelledby="sourceModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="sourceModalLabel">Create Source</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="sourceForm" method="POST" action="{{ route('sources.store') }}">
                                                        @csrf
                                                        <input type="hidden" name="_method" id="sourceFormMethod" value="POST">

                                                        <div id="sourceFieldsWrapper">
                                                            <div class="source-item mb-3 d-flex gap-2">
                                                                <div class="flex-grow-1">
                                                                    <label class="form-label">Source</label>
                                                                    <input name="sources[0][name]" class="form-control" required />
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <label class="form-label">Description</label>
                                                                    <input name="sources[0][description]" class="form-control" required />
                                                                </div>
                                                                <button type="button" class="btn btn-danger btn-remove-source align-self-end">Remove</button>
                                                            </div>
                                                        </div>

                                                        <button type="button" class="btn btn-secondary mb-3" id="addSourceBtn">Add Another Source</button>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save Sources</button>
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
                                                <th>Source</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sources as $index => $source)
                                                <tr>
                                                    <th scope="row">{{ $index + 1 }}</th>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{ $source->source }}</h6>
                                                    </td>
                                                    <td>{{ $source->description }}</td>
                                                    <td>
                                                        <a href="javascript:void(0);" 
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill"
                                                            data-source-id="{{ $source->id }}" 
                                                            data-source-name="{{ $source->source }}" 
                                                            data-source-description="{{ $source->description }}"
                                                            onclick="openSourceModal(this)">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" 
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                            data-bs-toggle="tooltip" 
                                                            title="Delete"
                                                            onclick="confirmDelete('delete-source-{{ $source->id }}', 'Delete Source?', 'Are you sure you want to delete this source?')">
                                                            <i class="ti ti-trash"></i>
                                                        </a>

                                                        <form id="delete-source-{{ $source->id }}" 
                                                            action="{{ route('sources.destroy', $source->id) }}" 
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
            
            </div>
        </div>
    </div>
</div>

<!-- Edit Source Modal -->
<div class="modal fade" id="editSourceModal" tabindex="-1" aria-labelledby="editSourceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header rounded-0"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="modal-title" id="editSourceModalLabel">Edit Source</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSourceForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Source</label>
                        <input type="text" name="name" id="editSourceName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" id="editSourceDescription" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Source</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS bundle (includes Popper) -->
<script>
    let sourceIndex = 1;

    document.getElementById('addSourceBtn').addEventListener('click', function () {
        const wrapper = document.getElementById('sourceFieldsWrapper');

        const newItem = document.createElement('div');
        newItem.classList.add('source-item', 'mb-3', 'd-flex', 'gap-2');
        newItem.innerHTML = `
            <div class="flex-grow-1">
                <label class="form-label">Source</label>
                <input name="sources[${sourceIndex}][name]" class="form-control" required />
            </div>
            <div class="flex-grow-1">
                <label class="form-label">Description</label>
                <input name="sources[${sourceIndex}][description]" class="form-control" required />
            </div>
            <button type="button" class="btn btn-danger btn-remove-source align-self-end">Remove</button>
        `;
        wrapper.appendChild(newItem);
        sourceIndex++;
    });

    document.getElementById('sourceFieldsWrapper').addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-source')) {
            e.target.closest('.source-item').remove();
        }
    });
</script>
<script>
    function openSourceModal(el) {
        let id = el.getAttribute("data-source-id");
        let name = el.getAttribute("data-source-name");
        let description = el.getAttribute("data-source-description");

        // Fill modal inputs
        document.getElementById("editSourceName").value = name;
        document.getElementById("editSourceDescription").value = description;

        // Update form action dynamically
        let form = document.getElementById("editSourceForm");
        form.action = "{{ url('sources') }}/update/" + id; // route to update

        // Show modal
        let modal = new bootstrap.Modal(document.getElementById("editSourceModal"));
        modal.show();
    }
</script>

@endsection
