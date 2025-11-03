@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Languages</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                        <form action="{{ route('languages') }}" method="GET">
                                            <div class="d-flex align-items-center">
                                                <div class="input-icon-start position-relative me-2">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-search"></i>
                                                    </span>
                                                    <input type="text" id="language-search" name="search"
                                                        value="{{ request('search') }}" class="form-control shadow-sm"
                                                        placeholder="Search">
                                                </div>
                                                <div>
                                                    <button class="btn btn-primary" type="submit">Search</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="text-end d-flex">
                                            <a href="javascript:void(0);"
                                                class="btn btn-primary text-white ms-2 fs-13 btn-md" data-bs-toggle="modal"
                                                data-bs-target="#add_specialization"><i class="ti ti-plus me-1"></i>Add
                                                Language</a>
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade" id="add_specialization" tabindex="-1"
                                            aria-labelledby="addSpecializationLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header rounded-0"
                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title" id="addSpecializationLabel">Add Language
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('languages.store') }}" method="POST">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="language" class="form-label">Language</label>
                                                                <input id="language" name= "language"
                                                                    class="form-control mb-2" />
                                                                <label for="short_code" class="form-label">Short
                                                                    Code</label>
                                                                <input id="short_code" name= "short_code"
                                                                    class="form-control mb-2" />
                                                                <label for="country_code" class="form-label">Country
                                                                    Code</label>
                                                                <input id="country_code" name= "country_code"
                                                                    class="form-control" />
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save</button>
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
                                                    <th>Language</th>
                                                    <th>Short Code</th>
                                                    <th>Country Code</th>
                                                    <th>Status</th>
                                                    <th>IsRTL</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($languages as $language)
                                                    <tr>
                                                        <th scope="row">{{ $loop->iteration }}</th>
                                                        <td>{{ $language->language }}</td>
                                                        <td>{{ $language->short_code }}</td>
                                                        <td>{{ $language->country_code }}</td>
                                                        <td>
                                                            @if ($language->is_active == 'yes')
                                                                <span class="badge bg-primary status-badge"> Active</span>
                                                            @else
                                                                Inactive
                                                            @endif
                                                        </td>
                                                        <td>

                                                            <input class="form-check-input rtl-toggle" type="checkbox"
                                                                value="" id="checkDefault" name="is_rtl"
                                                                data-id="{{ $language->id }}"
                                                                {{ $language->is_rtl == 'yes' ? 'checked' : '' }}>

                                                        </td>
                                                        <td>
                                                            <div class="form-check form-switch mb-0">
                                                                <input class="form-check-input status-toggle"
                                                                    type="checkbox" role="switch" id="switchCheckDefault"
                                                                    name="is_active" data-id="{{ $language->id }}"
                                                                    {{ $language->is_active == 'yes' ? 'checked' : '' }}
                                                                    {{ $language->is_active == 'yes' ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
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

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".status-toggle").forEach(function(checkbox) {
                    checkbox.addEventListener("change", function() {
                        let languageId = this.getAttribute("data-id");
                        let status = this.checked ? 'yes' : 'no';
                        const route = "{{ route('languages.updateStatus', [':id']) }}";
                        const url = route.replace(':id', encodeURIComponent(languageId));
                        fetch(url, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    is_active: status
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data) {
                                    console.log("Status updated:", data.message);
                                    location.reload()
                                } else {
                                    alert("Failed to update status");
                                }
                            })
                            .catch(error => console.error("Error:", error));
                    });
                });
                document.querySelectorAll(".rtl-toggle").forEach(function(checkbox) {
                    checkbox.addEventListener("change", function() {
                        let languageId = this.getAttribute("data-id");
                        let status = this.checked ? 'yes' : 'no';
                        const route = "{{ route('languages.updateRtl', [':id']) }}";
                        const url = route.replace(':id', encodeURIComponent(languageId));
                        fetch(url, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    is_rtl: status
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data) {
                                    location.reload()
                                } else {
                                    alert("Failed to update status");
                                }
                            })
                            .catch(error => console.error("Error:", error));
                    });
                });
            });
        </script>
    @endsection
