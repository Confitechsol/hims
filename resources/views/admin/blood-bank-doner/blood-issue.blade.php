@extends('layouts.adminLayout')

@section('content')
<div class="row px-5 py-4">
    <div class="col-12 d-flex">

        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                    <div>
                        <h4 class="fw-bold mb-0">Blood Issue Details</h4>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                            data-bs-toggle="modal" data-bs-target="#add_shift">
                            <i class="ti ti-plus me-1"></i> Issue Blood
                        </a>

                        

                    </div>
                </div>
            </div>

            <div class="card-body">
    @if($bloodissues->isEmpty())
        <p class="text-center">No Donor List found.</p>
    @else
        <div class="table-responsive table-nowrap">
            <table class="table border table-striped align-middle">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Bill No</th>
                        <th>Issue Date</th>
                        <th>Received To</th>
                        <th>Blood Group</th>
                        <th>Gender</th>
                        <th>Donor Name</th>
                        <th>Bags</th>
                        <th>Net Amount (INR)</th>
                        <th>Paid Amount (INR)</th>
                        <th>Balance Amount (INR)</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($bloodissues as $index => $issue)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $issue->bill_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($issue->date_of_issue)->format('d-m-Y') }}</td>
                            <td>{{ $issue->patient->patient_id }}</td>
                            <td>{{ $issue->blood_donor_cycle_id }}</td>
                            <td>{{ $issue->patient->gender }}</td>
                            <td>{{ $issue->blood_donor_cycle_id  }}</td>
                            <td>{{ $issue->bags }}</td>
                            <td>{{ number_format($issue->net_amount, 2) }}</td>
                            <td>{{ number_format($issue->paid_amount, 2) }}</td>
                            <td>{{ number_format($issue->balance_amount, 2) }}</td>
                            {{-- 
                            <td>
                                <a href="{{ route('bloodissue.show', $issue->id) }}" class="btn btn-info btn-sm">View</a>
                            </td> 
                            --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

        </div>

    </div>
</div>



<script>
function openEditModal(id, name, dob, bloodGroupId, gender, fatherName, contactNo, address) {
    // Fill form fields
    document.getElementById('edit_donor_id').value = id;
    document.getElementById('edit_doner_name').value = name;
    document.getElementById('edit_dob').value = dob;
    document.getElementById('edit_blood_group').value = bloodGroupId;
    document.getElementById('edit_gender').value = gender;
    document.getElementById('edit_father_name').value = fatherName;
    document.getElementById('edit_contact_no').value = contactNo;
    document.getElementById('edit_address').value = address;

    // Set dynamic action URL
    document.getElementById('editDonorForm').action = "{{ route('bloodBank.updateDoner', ['id' => ':id']) }}".replace(':id', id);

    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('edit_donor'));
    modal.show();
}
</script>
<script>
    function confirmDelete(url) {
        Swal.fire({
            title: "Are you sure?",
            text: "This donor will be deleted permanently.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // ✅ Create and submit a form using DELETE method
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';

                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

@endsection
