@extends('layouts.adminLayout')

@section('content')
    <!-- ========================
        Start Page Content
    ========================= -->

    {{-- <div class="page-wrapper"> --}}

        <style>
            .modal-backdrop.show:nth-of-type(2) {
                z-index: 1060;
                /* higher backdrop for nested modal */
            }

            #new_patient {
                z-index: 1070;
                /* ensure new modal is above the first */
            }
        </style>

        <!-- Start Content -->
        <div class="content pb-0">


            <!-- row start -->
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                                <div>
                                    <h4 class="fw-bold mb-0">Issue Item Details</h4>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <div class="text-end d-flex">
                                        <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                            data-bs-toggle="modal" data-bs-target="#add_issue_item"><i
                                                class="ti ti-plus me-1"></i>Issue Item </a>
                                               
                                    </div>
                                    <!-- First Modal -->
                                    <div class="modal fade" id="add_issue_item" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('issue-items.store') }}" id="issueItemForm" enctype="multipart/form-data">
                                                    @csrf

                                                    <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title">Issue Item</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row gy-3 align-items-center">

                                                            {{-- Department Type --}}
                                                            <div class="col-md-3">
                                                                <label for="department_id" class="form-label fw-bold">User Department <span class="text-danger">*</span></label>
                                                                <select name="department_id" id="department_id" class="form-select" required>
                                                                    <option value="">Select Department</option>
                                                                @foreach ($departments as $department)
                                                                        <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            {{-- Item Category --}}
                                                            <div class="col-md-3">
                                                                <label for="item_category_id" class="form-label fw-bold">Item Category <span class="text-danger">*</span></label>
                                                                <select name="item_category_id" id="item_category" class="form-select" required>
                                                                    <option value="">Select Category</option>
                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}">{{ $category->item_category }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            {{-- Item --}}
                                                            <div class="col-md-3">
                                                                <label for="item_id" class="form-label fw-bold">Item <span class="text-danger">*</span></label>
                                                                <select name="item_id" id="item" class="form-select" required>
                                                                    <option value="">Select Item</option>
                                                                </select>
                                                            </div>

                                                            {{-- Quantity --}}
                                                            <div class="col-md-3">
                                                                <label for="quantity" class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control" name="quantity" id="quantity" min="1" required>
                                                            </div>

                                                            {{-- Issue To --}}
                                                            <div class="col-md-3">
                                                                <label for="issue_to" class="form-label fw-bold">Issue To <span class="text-danger">*</span></label>
                                                                <select name="issue_to" id="issue_to" class="form-select" required>
                                                                    <option value="">Select Staff</option>
                                                                    
                                                                </select>
                                                            </div>

                                                            {{-- Issued By --}}
                                                            <div class="col-md-3">
                                                                <label for="issue_by" class="form-label fw-bold">Issued By</label>
                                                                <input type="text" name="issue_by" id="issue_by" value="{{ Auth::user()->username ?? '' }}" class="form-control" readonly>
                                                            </div>

                                                            {{-- Issue Date --}}
                                                            <div class="col-md-3">
                                                                <label for="issue_date" class="form-label fw-bold">Issue Date</label>
                                                                <input type="date" name="issue_date" id="issue_date" class="form-control" value="{{ date('Y-m-d') }}">
                                                            </div>

                                                            {{-- Return Date --}}
                                                            <div class="col-md-3">
                                                                <label for="return_date" class="form-label fw-bold">Return Date</label>
                                                                <input type="date" name="return_date" id="return_date" class="form-control">
                                                            </div>

                                                            {{-- Note --}}
                                                            <div class="col-md-12">
                                                                <label for="note" class="form-label fw-bold">Note</label>
                                                                <textarea name="note" id="note" rows="2" class="form-control" placeholder="Enter remarks if any..."></textarea>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                                    
                                    <a href="{{ route('inventory-details')}}"
                                        class="btn btn-outline-primary d-inline-flex align-items-center"><i
                                            class="ti ti-menu me-1"></i>Inventory</a>
                                    <a href="{{ route('items')}}"
                                        class="btn btn-outline-primary d-inline-flex align-items-center"><i
                                            class="ti ti-menu me-1"></i>Item</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Table start -->
                            <div class="table-responsive table-nowrap">
                                <table class="table border">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Category</th>
                                            <!-- <th>Issue Type</th> -->
                                            <th>Issue-Return</th>
                                            <th>Issue-To</th>
                                            <th>Issued By</th>
                                            <th>Quantity</th>
                                            <th>Note</th>
                                            <th>Status</th>
                                            
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($itemIssues as $issue)
                                            <tr>
                                                <td>{{ $issue->item->name ?? '-' }}</td>
                                                <td>{{ $issue->category->item_category ?? '-' }}</td>
                                                <!-- <td>{{ $issue->department_id ?? '-' }}</td> -->
                                                <td>{{ ucfirst($issue->return_date) }}</td>
                                                <td>
                                                    {{ $issue->issuedTo ? $issue->issuedTo->name . ' ' . $issue->issuedTo->surname . ' (' . $issue->issuedTo->employee_id . ')' : '-' }}
                                                </td>

                                                <td>{{ $issue->issue_by ?? '-' }}</td>
                                                <td>{{ $issue->quantity ?? 0 }}</td>
                                                <td>{{ $issue->note ?? '-' }}</td>

                                                <td>
                                                    @if ($issue->is_returned === 'yes')
                                                        <span class="badge bg-success">Returned</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Issued</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <a href="javascript:void(0);" 
                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill editIssueBtn"
                                                        data-id="{{ $issue->id }}" data-bs-toggle="tooltip" title="Edit">
                                                        <i class="ti ti-pencil"></i>
                                                    </a>

                                                    <a href="javascript:void(0);" 
                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                        onclick="if(confirm('Are you sure you want to delete this issue record?')) { document.getElementById('delete-issue-{{ $issue->id }}').submit(); }"
                                                        data-bs-toggle="tooltip" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </a>

                                                    <form id="delete-issue-{{ $issue->id }}" 
                                                        action="{{ route('issue-items.destroy', $issue->id) }}" 
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">No item issues found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table end -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>
         <!-- Edit Modal (nested) -->
        <!-- Edit Issue Modal -->
            <div class="modal fade" id="editIssueModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Item Issue</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editIssueForm">
                                @csrf
                                <input type="hidden" id="edit_issue_id" name="id">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Item Category</label>
                                        <select id="edit_item_category" name="item_category_id" class="form-control select2"></select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Item</label>
                                        <select id="edit_item" name="item_id" class="form-control select2"></select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Department</label>
                                        <select id="edit_department" name="department_id" class="form-control select2"></select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Issued To</label>
                                        <select id="edit_issued_to" name="issued_to" class="form-control select2"></select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Issued Date</label>
                                        <input type="date" id="edit_issued_date" name="issued_date" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Quantity</label>
                                        <input type="number" id="edit_quantity" name="quantity" class="form-control">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label>Remarks</label>
                                        <textarea id="edit_remarks" name="remarks" class="form-control"></textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>






        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('item_category');
    const itemSelect = document.getElementById('item');

    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        
        // Clear old items
        itemSelect.innerHTML = '<option value="">Select Item</option>';

        if (categoryId) {
             fetch(`{{ route('get.items', ':id') }}`.replace(':id', categoryId))
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            itemSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.textContent = 'No items found';
                        option.disabled = true;
                        itemSelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Error fetching items:', error);
                });
        }

    });
    $('#department_id').on('change', function () {
        let deptId = $(this).val();
        $('#issue_to').html('<option value="">Loading...</option>');

        if (deptId) {
            $.ajax({
                url: "{{ route('get-staff-by-department') }}",
                type: "GET",
                data: { department_id: deptId },
                success: function (data) {
                    $('#issue_to').html('<option value="">Select Staff</option>');
                    $.each(data, function (key, staff) {
                        $('#issue_to').append('<option value="' + staff.id + '">' + staff.name + ' ' + (staff.surname ?? '') + '</option>');
                    });
                }
            });
        } else {
            $('#issue_to').html('<option value="">Select Staff</option>');
        }
    });
   



});
$(document).on('click', '.editIssueBtn', function () {
    const id = $(this).data('id');

    $.ajax({
        url: "{{ route('issue-items.edit', ':id') }}".replace(':id', id),
        method: 'GET',
        success: function (data) {
            if (!data || !data.issue) {
                return alert('Issue record not found.');
            }

            const issue = data.issue;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editIssueModal'));
            modal.show();

            const form = $('#editIssueForm');
            form.attr('action', "{{ route('issue-items.update', ':id') }}".replace(':id', id));

            // Add PUT method if not present
            if (!form.find('input[name="_method"]').length) {
                form.append('<input type="hidden" name="_method" value="PUT">');
            }

            // Populate Item Category
            $('#edit_item_category').html('<option value="">Select Category</option>');
            $.each(data.categories, function(_, cat) {
                const selected = cat.id == issue.item_category_id ? 'selected' : '';
                $('#edit_item_category').append(`<option value="${cat.id}" ${selected}>${cat.item_category}</option>`);
            });

            // Populate Items dynamically for that category
            $.ajax({
                url: "{{ route('get-items-by-category') }}",
                type: 'GET',
                data: { category_id: issue.item_category_id },
                success: function(items) {
                    $('#edit_item').html('<option value="">Select Item</option>');
                    $.each(items, function(_, item) {
                        const selected = item.id == issue.item_id ? 'selected' : '';
                        $('#edit_item').append(`<option value="${item.id}" ${selected}>${item.name}</option>`);
                    });
                }
            });

            // Populate Departments
            $('#edit_department').html('<option value="">Select Department</option>');
            $.each(data.departments, function(_, dept) {
                const selected = dept.id == issue.department_id ? 'selected' : '';
                $('#edit_department').append(`<option value="${dept.id}" ${selected}>${dept.department_name}</option>`);
            });

            // Populate Staff based on department
            $.ajax({
                url: "{{ route('get-staff-by-department') }}",
                type: 'GET',
                data: { department_id: issue.department_id },
                success: function(staffList) {
                    $('#edit_issued_to').html('<option value="">Select Staff</option>');
                    $.each(staffList, function(_, staff) {
                        const selected = staff.id == issue.issued_to ? 'selected' : '';
                        $('#edit_issued_to').append(`<option value="${staff.id}" ${selected}>${staff.name} ${staff.surname ?? ''}</option>`);
                    });
                }
            });

            // Populate other fields
            $('#edit_issue_id').val(issue.id);
            $('#edit_quantity').val(issue.quantity);
            $('#edit_issued_date').val(issue.issue_date);
            $('#edit_remarks').val(issue.note ?? '');
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Error fetching issue item details.');
        }
    });
});

</script>








@endsection