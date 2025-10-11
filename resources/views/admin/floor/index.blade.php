@extends('layouts.adminLayout')

@section('content')
    <div class="container">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Floors</h5>
            </div>

            <div class="card-body">
            <x-table-actions.actions id="floors" name="Floor" />
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}</div>
                @endif
                {{-- Floor Table --}}
                <table class="table table-bordered" id="floors">
                    <thead>
                        <tr>
                            <th>Floor Name</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($floors as $floor)
                            <tr>
                                <td>{{ $floor->name }}</td>
                                <td>
                                    {{-- Edit Button --}}
                                    <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="{{ $floor->id }}" data-name="{{ $floor->name }}">
                                        <i class="ti ti-pencil"></i>
                                    </button>

                                    {{-- Delete Button --}}
                                    <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-id="{{ $floor->id }}"
                                        data-name="{{ $floor->name }}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('floors.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Floor</h5>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="name" placeholder="Enter floor name" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('floors.update') }}" method="POST" class="modal-content">
                @csrf @method('PUT')
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Floor</h5>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="name" id="edit-name" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('floors.destroy') }}" method="POST" class="modal-content">
                @csrf @method('DELETE')
                <input type="hidden" name="id" id="delete-id">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Floor</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="delete-name"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS to populate modals --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');

            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('edit-id').value = button.getAttribute('data-id');
                document.getElementById('edit-name').value = button.getAttribute('data-name');
            });

            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('delete-id').value = button.getAttribute('data-id');
                document.getElementById('delete-name').textContent = button.getAttribute('data-name');
            });
        });
    </script>
@endsection
