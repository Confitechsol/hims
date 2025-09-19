@extends('layouts.adminLayout')
@section('content')
<div class="container">
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
            <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Bed Groups</h5>
        </div>

        <div class="card-body">
            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
            {{-- Create Button --}}
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Add Bed
                Group</button>

            {{-- Table --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bedGroups as $group)
                        <tr>
                            <td>{{ $group->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal"
                                    data-id="{{ $group->id }}" data-name="{{ $group->name }}" data-floor="{{$group->floor}}" data-color="{{$group->color}}" data-cost="{{$group->cost}}" data-description="{{$group->description}}">
                                    Edit
                                </button>

                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                    data-id="{{ $group->id }}" data-name="{{ $group->name }}">
                                    Delete
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
        <form action="{{ route('bed-groups.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Bed Group</h5>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Bed Group Name" required>
                </div>
                <div class="form-group mb-2">
                    <label for="" class="form-label">Floor</label>
                    <select name="floor" id="" class="form-select" required>
                        <option value="">Select Floor</option>
                        @foreach ($floors as $floor)
                            <option value="{{$floor->id}}">{{$floor->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="" class="form-label">Color</label>
                    <input type="color" class="form-control" name="color">
                </div>
                <div class="form-group mb-2">
                    <label for="" class="form-label">Bed Cost</label>
                    <input type="text" class="form-control" name="bed_cost" >
                </div>
                <div class="form-group mb-2">
                    <label for="" class="form-label">Description</label>
                    <input type="text" class="form-control" name="description">
                </div>
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
        <form action="{{ route('bed-groups.update') }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-header">
                <h5 class="modal-title">Edit Bed Group</h5>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="edit-name" placeholder="Bed Group Name" required>
                </div>
                <div class="form-group mb-2">
                    <label for="" class="form-label">Floor</label>
                    <select name="floor" id="edit-floor" class="form-select" required>
                        <option value="">Select Floor</option>
                        @foreach ($floors as $floor)
                            <option value="{{$floor->id}}">{{$floor->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="" class="form-label">Color</label>
                    <input type="color" id="edit-color" class="form-control" name="color">
                </div>
                <div class="form-group mb-2">
                    <label for="" class="form-label">Bed Cost</label>
                    <input type="text" id="edit-cost" class="form-control" name="bed_cost" >
                </div>
                <div class="form-group mb-2">
                    <label for="" class="form-label">Description</label>
                    <input type="text" id="edit-description" class="form-control" name="description">
                </div>
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
        <form action="{{ route('bed-groups.destroy') }}" method="POST" class="modal-content">
            @csrf @method('DELETE')
            <input type="hidden" name="id" id="delete-id">
            <div class="modal-header">
                <h5 class="modal-title">Delete Bed Group</h5>
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
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('edit-id').value = button.getAttribute('data-id');
            document.getElementById('edit-name').value = button.getAttribute('data-name');
            document.getElementById('edit-floor').value = button.getAttribute('data-floor');
            document.getElementById('edit-color').value = button.getAttribute('data-color');
            document.getElementById('edit-cost').value = button.getAttribute('data-cost');
            document.getElementById('edit-description').value = button.getAttribute('data-description');
        });

        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('delete-id').value = button.getAttribute('data-id');
            document.getElementById('delete-name').textContent = button.getAttribute('data-name');
        });
    });
</script>

@endsection