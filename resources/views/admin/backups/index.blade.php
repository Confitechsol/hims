@extends('layouts.adminLayout')
@section('content')
<div class="page-wrapper">
    <!-- Start Content -->
    <div class="content" id="profilePage">

     <!-- Page Header -->
     <div class="mb-3 border-bottom pb-3">
         <h4 class="fw-bold mb-0">Settings</h4>
     </div>
     <!-- End Page Header -->

     <div class="card">
         <div class="card-body p-0">
             <div class="settings-wrapper d-flex">
                 <div class="card flex-fill mb-0 border-0 bg-light-500 shadow-none">
                     <div class="card-header border-bottom px-0 mx-3">
                         <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                             <h5 class="fw-bold">Database Backup</h5>
                             {{-- <a href="javascript:void(0);" class="btn btn-primary"><i class="ti ti-send me-1"></i>Send Test Mail</a> --}}
                         </div>
                     </div>
                     <div class="card-body px-0 mx-3">
                        <a href="{{ route('database.backup') }}" class="btn btn-info mb-3">Create New Backup</a>
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @elseif(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Size (KB)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($backups as $backup)
                                    <tr>
                                        <td>{{ $backup['name'] }}</td>
                                        <td>{{ number_format($backup['size'] / 1024, 2) }}</td>
                                        <td>
                                            <!-- Download -->
                                            <a href="{{ $backup['url'] }}" class="btn btn-sm btn-success">Download</a>
                    
                                            <!-- Restore -->
                                            <form action="{{ route('database.restore') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="filename" value="{{ $backup['name'] }}">
                                                <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to restore this backup?')">Restore</button>
                                            </form>
                    
                                            <!-- Delete -->
                                            <form action="{{ route('database.delete', $backup['name']) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this backup?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3">No backups found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                     </div>
                 </div>
             </div>

         </div><!-- end card body -->
     </div><!-- end card -->
                     
 </div>
 <!-- End Content -->
</div>     
@endsection