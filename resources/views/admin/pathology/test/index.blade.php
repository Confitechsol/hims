@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-vial me-2"></i>Pathology Test List
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                        <div class="input-icon-start position-relative me-2">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" class="form-control shadow-sm" id="searchTest" placeholder="Search Test">
                                        </div>

                                        <div class="page_btn d-flex">
                                            <a href="{{ route('pathology.test.create') }}" class="btn btn-primary text-white ms-2 fs-13 btn-md">
                                                <i class="ti ti-plus me-1"></i>Add Pathology Test
                                            </a>
                                        </div>
                                    </div>

                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Test Name</th>
                                                    <th>Short Name</th>
                                                    <th>Test Type</th>
                                                    <th>Category</th>
                                                    <th>Charge Category</th>
                                                    <th>Charge Name</th>
                                                    <th>Sub Category</th>
                                                    <th>Method</th>
                                                    <th>Report Days</th>
                                                    <th>Tax (%)</th>
                                                    <th>Charge (INR)</th>
                                                    <th>Amount (INR)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="testTableBody">
                                                @forelse($tests as $test)
                                                    <tr>
                                                        <td>{{ $test->id }}</td>
                                                        <td class="fw-bold">{{ $test->test_name }}</td>
                                                        <td>{{ $test->short_name }}</td>
                                                        <td>{{ $test->test_type ?? '-' }}</td>
                                                        <td>{{ $test->category->category_name ?? '-' }}</td>
                                                        <td>{{ $test->chargeCategory->name ?? ($test->charge && $test->charge->category ? $test->charge->category->name : '-') }}</td>
                                                        <td>{{ $test->charge->name ?? '-' }}</td>
                                                        <td>{{ $test->sub_category ?? '-' }}</td>
                                                        <td>{{ $test->method ?? '-' }}</td>
                                                        <td>{{ $test->report_days ?? '-' }}</td>
                                                        <td>{{ $test->charge && $test->charge->taxCategory ? number_format($test->charge->taxCategory->percentage, 2) : '0.00' }}%</td>
                                                        <td>₹{{ number_format($test->standard_charge ?? ($test->charge ? $test->charge->standard_charge : 0), 2) }}</td>
                                                        <td class="fw-bold">₹{{ number_format($test->amount ?? ($test->charge ? ($test->charge->standard_charge + ($test->charge->standard_charge * ($test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0) / 100)) : 0), 2) }}</td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <a href="{{ route('pathology.test.show', $test->id) }}" class="btn btn-sm btn-info text-white" title="View">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                                <a href="{{ route('pathology.test.edit', $test->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                                                    <i class="ti ti-edit"></i>
                                                                </a>
                                                                <form action="{{ route('pathology.test.destroy', $test->id) }}" method="POST" class="d-inline" onsubmit="return confirmDeleteForm(event, 'Delete Test?', 'Are you sure you want to delete this test?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger text-white" title="Delete">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="12" class="text-center py-4">
                                                            <div class="text-muted">
                                                                <i class="ti ti-inbox fs-48 mb-2"></i>
                                                                <p>No pathology tests found. Add your first test!</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchTest');
            const tableBody = document.getElementById('testTableBody');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = tableBody.querySelectorAll('tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });
    </script>
@endsection

