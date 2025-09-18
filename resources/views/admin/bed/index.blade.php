@extends('layouts.adminLayout')
@section('content')
    <!-- Start Content -->
    <div class="content">
        <div class="row justify-content-center">

            {{-- Settings Form --}}
            <div class="col-md-12">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Bed Status</h5>
                    </div>
    
                    <div class="card-body">
                        <!--  Start Table -->
        <div class="table-responsive">
            <table class="table datatable table-nowrap">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Bed Type</th>
                        <th>Bed Group</th>
                        <th>Floor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beds as $bed)
                    <tr>
                        <td>{{$bed->name}}</td>
                        <td>{{ $bed->bedType->name ?? 'N/A' }}</td>                                
                        <td>{{ $bed->bedGroup->name ?? 'N/A' }}</td>
                        <td>{{ $bed->bedGroup->floor ?? 'N/A' }}</td>
                        <td><span class="badge badge-soft-{{ $bed->is_active == "yes" ? 'success' : 'danger' }} rounded text-{{ $bed->is_active == "yes" ? 'success' : 'danger' }} border border-{{ $bed->is_active == "yes" ? 'success' : 'danger' }} fs-13 fw-medium">{{ $bed->is_active == "yes" ? 'Available' : 'Alloted' }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!--  End Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection