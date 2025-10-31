{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <!-- row start -->

    <div class="row p-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>
                        Certificate Template List
                    </h5>
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
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <div class="text-end d-flex">
                                                <a href="certificate" class="btn btn-primary text-white ms-2 btn-md"><i
                                                        class="fa-solid fa-sliders pe-1"></i> Add Certificate Template</a>
                                            </div>

                                        </div>
                                    </div>


                                    <!-- Table start -->
                                    <div class="table-responsive table-nowrap">
                                        <table class="table border">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Certificate Template Name</th>
                                                    <th>Background Image</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Table end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection