<!-- Bed Modal -->
<div class="modal fade" id="bedModal" tabindex="-1" aria-labelledby="bedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #CB6CE6; color: white;">
                <h5 class="modal-title">Bed Management</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @foreach($grouped as $floor => $groups)
    <div class="card shadow-lg border-0 mt-4">
        <div class="card-header">
            <span class="badge pill bg-primary">Floor {{ $floor }}</span>
        </div>

        @foreach($groups as $groupName => $beds)
            <div class="px-3 pt-2">
                <span class="badge pill bg-warning">{{ $groupName }}</span>
            </div>

            <div class="card-body">
                <div class="row align-items-center gy-4">
                    
                    @foreach($beds as $bed)
                        <div class="col-md-3">
                            <a href="#">
                                <div
                                    class="{{ $bed['is_occupied'] ? 'bg-danger' : 'bg-success' }} text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                    data-bs-toggle="tooltip"
                                    title="{{ $bed['is_occupied'] 
                                        ? 'Occupied: '.($bed['patient_name'] ?? 'Unknown') 
                                        : 'Available Bed' }}">
                                    
                                    <i class="fa-solid fa-bed"></i>
                                    <p>{{ $bed['name'] }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach
    </div>
@endforeach
                {{-- <div class="card shadow-lg border-0 mt-4">
                    <div class="card-header">
                        <span class="badge pill bg-primary">Floor 4</span>
                        <span class="badge pill bg-warning">Test ward</span>
                    </div>

                    <div class="card-body">
                        <div class="row align-items-center gy-4">
                            <div class="col-md-3">
                                <a href="#">
                                    <div class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 1</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 2</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                         data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 3</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 4</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 5</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 6</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 7</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 8</p>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card shadow-lg border-0 mt-4">
                    <div class="card-header">
                        <span class="badge pill bg-primary">Floor 3</span>
                    </div>

                    <div class="card-body">
                        <div class="row align-items-center gy-4">
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 1</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 2</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 3</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 4</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 5</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 6</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 7</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 8</p>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card shadow-lg border-0 mt-4">
                    <div class="card-header">
                        <span class="badge pill bg-primary">Floor 2</span>
                        <span class="badge pill bg-warning">General Ward</span>
                    </div>

                    <div class="card-body">
                        <div class="row align-items-center gy-4">
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 1</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 2</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 3</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 4</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 5</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 6</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 7</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 8</p>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div> --}}
                {{-- <div class="card shadow-lg border-0 mt-4">
                    <div class="card-header">
                        <span class="badge pill bg-warning">Day Care Unit</span>
                    </div>

                    <div class="card-body">
                        <div class="row align-items-center gy-4">
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 1</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 2</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 3</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 4</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 5</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 6</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 7</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 8</p>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div> --}}
                {{-- <div class="card shadow-lg border-0 mt-4">
                    <div class="card-header">
                        <span class="badge pill bg-primary">Floor 1</span>
                        <span class="badge pill bg-warning">ICU (Intensive Care Unit)</span>
                    </div>

                    <div class="card-body">
                        <div class="row align-items-center gy-4">
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 1</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 2</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 3</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 4</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 5</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 6</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-success text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Available Bed">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 7</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#">
                                    <div
                                        class="bg-danger text-white bg-opacity-75 bg-gradient p-2 rounded d-flex align-items-center gap-2"
                                        data-bs-toggle="tooltip" title="Occupied: Rahul Sharma">
                                        <i class="fa-solid fa-bed"></i>
                                        <p>Bed 8</p>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div> --}}
            </div>

            </form>
        </div>
    </div>
</div>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>