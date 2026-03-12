@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-plus-circle me-2"></i>Add Medicine
                    </h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('medicine-master.store') }}" method="POST" id="medicineForm">
                        @csrf

                        <div class="text-end mb-3">
                            <button type="button" class="btn btn-sm btn-primary" id="addMedicine">
                                <i class="fas fa-plus"></i> Add Medicine
                            </button>
                        </div>

                        <div id="medicineContainer">

                            <div class="medicine-item border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 badge bg-primary">
                                        Medicine <span class="medicine-badge">1</span>
                                    </h6>

                                    {{-- <button type="button" class="btn btn-danger btn-sm removeMedicine">
                                        <i class="fas fa-trash"></i>
                                    </button> --}}
                                </div>
                                <div class="row mb-3">

                                    <div class="col-md-4">
                                        <label class="form-label">Medicine Name *</label>
                                        <input type="text" name="medicine_name[]" class="form-control" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Price</label>
                                        <input type="number" step="0.01" name="medicine_price[]" class="form-control"
                                            min="0">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Manufacturer Name</label>
                                        <input type="text" name="manufacturer_name[]" class="form-control">
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="form-label">Pack Size Label</label>
                                        <input type="text" name="pack_size_label[]" class="form-control">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Short Composition 1</label>
                                        <input type="text" name="short_composition1[]" class="form-control">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Short Composition 2</label>
                                        <input type="text" name="short_composition2[]" class="form-control">
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('medicine-master') }}" class="btn btn-secondary">Cancel</a>

                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check"></i> Save
                            </button>
                        </div>

                    </form>
                    {{-- <form action="{{ route('medicine-master.store') }}" method="POST" id="medicineForm">
                        @csrf

                        <!-- Row 1 -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Medicine Name <span class="text-danger">*</span></label>
                                <input type="text" name="medicine_name" class="form-control"
                                    value="{{ old('medicine_name') }}" required maxlength="50" placeholder="Medicine Name">
                                @error('medicine_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" name="medicine_price" class="form-control"
                                    value="{{ old('medicine_price') }}" min="0" placeholder="Price">
                                @error('medicine_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Manufacturer Name</label>
                                <input type="text" name="manufacturer_name" class="form-control"
                                    value="{{ old('manufacturer_name') }}" placeholder="Manufacturer Name">
                                @error('manufacturer_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>

                        <!-- Row 2 -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Pack Size Label</label>
                                <input type="text" name="pack_size_label" class="form-control"
                                    value="{{ old('pack_size_label') }}" placeholder="Pack Size Label">
                                @error('pack_size_label')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Short Composition 1</label>
                                <input type="text" name="short_composition1" class="form-control"
                                    value="{{ old('short_composition1') }}" maxlength="25"
                                    placeholder="Short Composition 1">
                                @error('short_composition1')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Short Composition 2</label>
                                <input type="text" name="short_composition2" class="form-control"
                                    value="{{ old('short_composition2') }}" maxlength="25"
                                    placeholder="Short Composition 2">
                                @error('short_composition2')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('medicine-master') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check"></i>&nbsp;Save
                            </button>
                        </div>
                    </form> --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateMedicineNumbers() {

            const items = document.querySelectorAll('.medicine-item');

            items.forEach((item, index) => {
                item.querySelector('.medicine-badge').innerText = index + 1;
            });

        }

        document.getElementById('addMedicine').addEventListener('click', function() {

            const container = document.getElementById('medicineContainer');

            const html = `
    <div class="medicine-item border rounded p-3 mb-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 badge bg-primary">
                Medicine <span class="medicine-badge"></span>
            </h6>

            <button type="button" class="btn btn-danger btn-sm removeMedicine">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <div class="row mb-3">

            <div class="col-md-4">
                <label class="form-label">Medicine Name *</label>
                <input type="text" name="medicine_name[]" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="medicine_price[]" class="form-control" min="0">
            </div>

            <div class="col-md-4">
                <label class="form-label">Manufacturer Name</label>
                <input type="text" name="manufacturer_name[]" class="form-control">
            </div>

        </div>

        <div class="row">

            <div class="col-md-4">
                <label class="form-label">Pack Size Label</label>
                <input type="text" name="pack_size_label[]" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Short Composition 1</label>
                <input type="text" name="short_composition1[]" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Short Composition 2</label>
                <input type="text" name="short_composition2[]" class="form-control">
            </div>

        </div>

    </div>
    `;

            container.insertAdjacentHTML('beforeend', html);

            updateMedicineNumbers();

        });


        document.addEventListener('click', function(e) {

            if (e.target.closest('.removeMedicine')) {

                e.target.closest('.medicine-item').remove();

                updateMedicineNumbers();

            }

        });

        updateMedicineNumbers();
    </script>
@endsection
