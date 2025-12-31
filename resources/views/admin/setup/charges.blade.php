{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

        <style>
            .input-group .input-group-addon {
                border-radius: 0;
                border: 1px solid #d2d6de;
                background-color: #d3a2e03d;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0 10px;
            }

            .input-group {
                position: relative;
                display: table;
                border-collapse: separate;
            }

            .form-select {
                padding: 0.5rem 0.75rem !important;
            }
        </style>

        <div class="row justify-content-center">
            {{-- Settings Form --}}
            <div class="col-md-11">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Charges Details List</h5>
                    </div>

                    <div class="card-body">


                        {{-- Hospital Name & Code --}}
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="card">

                                    <div class="card-body">
                                    <x-table-actions.actions id="charges" name="Charges" />
                                            @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                        @endif
                                        @if(session('error'))
                                            <div class="alert alert-danger">
                                                {{session('error')}}
                                            </div>
                                        @endif
                                        @if(session('success'))
                                            <div class="alert alert-success">
                                                {{session('success')}}
                                            </div>
                                        @endif
                                            <!-- Modal -->
                                            <div class="modal fade" id="createModal" tabindex="-1"
                                                aria-labelledby="addSpecializationLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                                    <div class="modal-content modal-xl">
                                                        <div class="modal-header rounded-0 modal-xl"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Charges</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('charges.store')  }}" method="POST">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="row gy-3">
                                                                            <div class="col-md-6">
                                                                                <label for="" class="form-label">Charge
                                                                                    Type <span
                                                                                        class="text-danger">*</span></label>
                                                                                <select name="charge_type" id="charge_type"
                                                                                onchange="handleCategory(this,'charge_category')"
                                                                                    class="form-select" required>
                                                                                    <option value="">Select</option>
                                                                                    @foreach ($charge_types as $charge_type)
                                                                                       <option value="{{$charge_type->id}}">{{$charge_type->charge_type}}</option> 
                                                                                    @endforeach

                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="" class="form-label">Charge
                                                                                    Category <span
                                                                                        class="text-danger">*</span></label>
                                                                                <select name="charge_category"
                                                                                    id="charge_category" class="form-select"
                                                                                    required>
                                                                                    <option value="">Select</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="" class="form-label">Tax
                                                                                    Category <span
                                                                                        class="text-danger">*</span></label>
                                                                                <select name="tax_category" id="tax_category"
                                                                                    class="form-select" autocomplete="off"
                                                                                    required
                                                                                    onchange="taxCategory(this,'tax_percentage')"
                                                                                    >
                                                                                    <option value="">Select</option>
                                                                                    @foreach ($charge_tax_category_id as $charge_tax_category_ids)
                                                                                        <option value="{{$charge_tax_category_ids->id}}">{{$charge_tax_category_ids->name}}</option>
                                                                                    @endforeach


                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="" class="form-label">Tax</label>
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control"
                                                                                        name="tax_percentage"
                                                                                        id="tax_percentage" disabled>
                                                                                    <span class="input-group-addon "> %</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="" class="form-label">Standard Charge
                                                                                    (INR) <span
                                                                                        class="text-danger">*</span></label>

                                                                                <input type="text" class="form-control"
                                                                                    name="standard_charge" id="standard_charge"
                                                                                    required>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for=""
                                                                                    class="form-label">Description</label>

                                                                                <textarea name="description" id="description"
                                                                                    class="form-control"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="row gy-3">
                                                                            <div class="col-md-4">
                                                                                <label for="" class="form-label">Unit Type <span
                                                                                        class="text-danger">*</span></label>
                                                                                <select name="unit_type" id="unit_type"
                                                                                    class="form-select" autocomplete="off"
                                                                                    required>
                                                                                    @foreach ($charge_unit as $charge_units)
                                                                                        <option value="{{$charge_units->id}}">{{$charge_units->unit}}</option>
                                                                                    @endforeach


                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <label for="" class="form-label">Charge
                                                                                    Name<span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" name="charge_name"
                                                                                    id="charge_name" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label>Scheduled Charges For TPA</label>
                                                                                <button type="button" class="btn btn-secondary plusign float-end"
                                                                                    onclick="apply_to_all()">Apply To
                                                                                    All</button>
                                                                                <div class="chargesborbg form-control mt-4">
                                                                                    <div class="form-group">
                                                                                        <table class="printablea4">
                                                                                            <tbody>
                                                                                            @foreach ($organisation_names as $organisation_name)

                                                                                              <tr id="schedule_charge">
                                                                                                    <input type="hidden"
                                                                                                        name="schedule_charge_id[]"
                                                                                                        value="{{$organisation_name->id}}">
                                                                                                    <td class="col-sm-8"
                                                                                                        style="vertical-align: bottom; text-align: left; padding-right: 20px;">
                                                                                                        {{$organisation_name->organisation_name}}
                                                                                                    </td>
                                                                                                    <td class="col-sm-4">
                                                                                                        <input type="text"
                                                                                                            name="schedule_charge_{{$organisation_name->id}}"
                                                                                                            id="schedule_charge_{{$organisation_name->id}}"
                                                                                                            class="form-control schedule_charge">
                                                                                                    </td>
                                                                                                </tr>

                                                                                            @endforeach


                                                                                            </tbody>
                                                                                        </table>
                                                                                        <span class="text-danger"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
                                            <table class="table mb-0" id="charges">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Charge Category</th>
                                                        <th>Charge Type</th>
                                                        <th>Unit</th>
                                                        <th>Tax(%)</th>
                                                        <th>Standard Charge (INR)</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($charges as $charge)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold"> {{$charge->name}}</h6>
                                                        </td>
                                                        <td>{{$charge->category['name']}}</td>
                                                        <td>{{$charge->category["chargeType"]->charge_type}}</td>
                                                        <td>{{$charge->unit['unit']}}</td>
                                                        <td>{{$charge->taxCategory['percentage'] ?? ""}}</td>
                                                        <td>{{$charge->standard_charge}}</td>
                                                        <td>
                                                            <a href="javascript: void(0);"
                                                           onclick="editCharge({{$charge->id}})"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                <i class="ti ti-pencil"></i></a>
                                                                <form class="d-inline" action="{{ route('charges.destroy') }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="id" value="{{$charge->id}}">
                                                                    <button onclick="return confirm('Are you sure you want to delete this charge?')" type="submit" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="m-3">
                            <strong>Total Charges: <span id="bed-count">{{ count($charges) }}</span></strong>
                        </div>
                                    </div> <!-- end card-body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit_charges" tabindex="-1"
                                                aria-labelledby="EditSpecializationLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                                    <div class="modal-content modal-xl">
                                                        <div class="modal-header rounded-0 modal-xl"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Edit Charges</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('charges.update')  }}" method="POST">
                                                                @csrf
                                                                @method('put')
                                                                <input type="hidden" name="charge_id">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="row gy-3">
                                                                            <div class="col-md-6">
                                                                                <label for="" class="form-label">Charge
                                                                                    Type <span
                                                                                        class="text-danger">*</span></label>
                                                                                <select name="charge_type" id="charge_type"
                                                                                onchange="handleCategory(this,'edit_charge_category')"
                                                                                    class="form-select" required>
                                                                                    <option value="">Select</option>
                                                                                    @foreach ($charge_types as $charge_type)
                                                                                       <option value="{{$charge_type->id}}">{{$charge_type->charge_type}}</option> 
                                                                                    @endforeach

                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="" class="form-label">Charge
                                                                                    Category <span
                                                                                        class="text-danger">*</span></label>
                                                                                <select name="charge_category"
                                                                                    id="edit_charge_category" class="form-select"
                                                                                    required>
                                                                                    <option value="">Select</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="" class="form-label">Tax
                                                                                    Category <span
                                                                                        class="text-danger">*</span></label>
                                                                                <select name="tax_category" id="tax_category"
                                                                                    class="form-select" autocomplete="off"
                                                                                    required
                                                                                    onchange="taxCategory(this,'edit_tax_percentage')"
                                                                                    >
                                                                                    <option value="">Select</option>
                                                                                    @foreach ($charge_tax_category_id as $charge_tax_category_ids)
                                                                                        <option value="{{$charge_tax_category_ids->id}}">{{$charge_tax_category_ids->name}}</option>
                                                                                    @endforeach


                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="" class="form-label">Tax</label>
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control"
                                                                                        name="tax_percentage"
                                                                                        id="edit_tax_percentage" disabled>
                                                                                    <span class="input-group-addon "> %</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="" class="form-label">Standard Charge
                                                                                    (INR) <span
                                                                                        class="text-danger">*</span></label>

                                                                                <input type="text" class="form-control"
                                                                                    name="standard_charge" id="standard_charge"
                                                                                    required>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for=""
                                                                                    class="form-label">Description</label>

                                                                                <textarea name="description" id="description"
                                                                                    class="form-control"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="row gy-3">
                                                                            <div class="col-md-4">
                                                                                <label for="" class="form-label">Unit Type <span
                                                                                        class="text-danger">*</span></label>
                                                                                <select name="unit_type" id="unit_type"
                                                                                    class="form-select" autocomplete="off"
                                                                                    required>
                                                                                    @foreach ($charge_unit as $charge_units)
                                                                                        <option value="{{$charge_units->id}}">{{$charge_units->unit}}</option>
                                                                                    @endforeach


                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <label for="" class="form-label">Charge
                                                                                    Name<span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" name="charge_name"
                                                                                    id="charge_name" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label>Scheduled Charges For TPA</label>
                                                                                <button type="button" class="btn btn-secondary plusign float-end"
                                                                                    onclick="apply_to_all()">Apply To
                                                                                    All</button>
                                                                                <div class="chargesborbg form-control mt-4">
                                                                                    <div class="form-group">
                                                                                        <table class="printablea4">
                                                                                            <tbody>
                                                                                            @foreach ($organisation_names as $organisation_name)

                                                                                              <tr id="schedule_charge">
                                                                                                    <input type="hidden"
                                                                                                        name="schedule_charge_id[]"
                                                                                                        value="{{$organisation_name->id}}">
                                                                                                    <td class="col-sm-8"
                                                                                                        style="vertical-align: bottom; text-align: left; padding-right: 20px;">
                                                                                                        {{$organisation_name->organisation_name}}
                                                                                                    </td>
                                                                                                    <td class="col-sm-4">
                                                                                                        <input type="text"
                                                                                                            name="schedule_charge_{{$organisation_name->id}}"
                                                                                                            id="schedule_charge_{{$organisation_name->id}}"
                                                                                                            class="form-control schedule_charge">
                                                                                                    </td>
                                                                                                </tr>

                                                                                            @endforeach


                                                                                            </tbody>
                                                                                        </table>
                                                                                        <span class="text-danger"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
    <script>
            let chargeCategories = @json($chargeCategories);
        let charges = @json($charges);
        let organisation_charges = @json($organisation_charges);
        let organisation_names = @json($organisation_names);
        function handleCategory(charge_type,id){
            let charge_category = document.getElementById(id);
            let newData = chargeCategories.filter(item => item.charge_type_id == charge_type.value);
            let html = '<option value="">Select</option>';
            newData.map((item)=>{
                html+=`<option value="${item.id}">${item.name}</option>`;
            })
            charge_category.innerHTML = html;
        }

        function taxCategory(tax_category,tax_percentage){
            let charge_tax_categories = @json($charge_tax_category_id);
            let category = charge_tax_categories.filter(item  => item.id == tax_category.value);
            document.getElementById(tax_percentage).value = category[0].percentage;
        }
        function editCharge(id){
            let charge = charges.find(item => item.id == id);
            let edit_charges = document.getElementById("edit_charges");
            edit_charges.querySelector("input[name='charge_id']").value = id;
            var myModal = new bootstrap.Modal(edit_charges);
            let categoryhtml = '<option value="">Select</option>';
            chargeCategories.map((item) => {
                if(item.charge_type_id == charge.category.charge_type_id){
                    categoryhtml+= `<option value="${item.id}">${item.name}</option>`
                }
            });
            edit_charges.querySelector("select[name='charge_category']").innerHTML = categoryhtml;
            myModal.show();
            edit_charges.querySelector("select[name='charge_type']").value = charge.category.charge_type_id;
            edit_charges.querySelector("select[name='charge_category']").value = charge.charge_category_id;
            edit_charges.querySelector("select[name='unit_type']").value = charge.unit.id;
            edit_charges.querySelector("input[name='charge_name']").value = charge.name;              
            edit_charges.querySelector("select[name='tax_category']").value = charge.tax_category_id;
            edit_charges.querySelector("input[name='tax_percentage']").value = charge.tax_category.percentage;
            edit_charges.querySelector("input[name='standard_charge']").value = charge.standard_charge;
            edit_charges.querySelector("textarea[name='description']").value = charge.description;
            organisation_names.map((item,index)=>{
                let name = `input[name='schedule_charge_${item.id}']`;
                let charge = organisation_charges.filter(innerItem => innerItem.charge_id == id && innerItem.org_id == item.id);
                if(charge.length > 0){
                    edit_charges.querySelector(name).value = charge[0].org_charge;
                }            
            });

        }    

    </script>

@endsection