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
            /* loader css */
            .loader-section{
                background-color: rgba(0,0,0,0.4);
                position:fixed;
                top:0;
                left:0;
                z-index:9999;
                width:100%;
                height:100%;
                display:none;
            }
            @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
            }
        </style>
    <!-- Loader -->
    <div id="loader" class="loader-section">
    <div style="display:block; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); z-index:1000;">
        <div style="border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite;"></div>
    </div>
    </div>
    <!-- Toast message box -->
    <div id="toast" style="display:none; position:fixed; top:20px; right:20px; background:#4CAF50; color:white; padding:10px 20px; border-radius:5px; z-index:1001;">
        <span id="toast-message"></span>
    </div>
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Charge Type List</h5>
                    </div>
                    <div class="card-body">
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
                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            {{session('success')}}
                                        </div>
                                    @endif
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                    <x-table-actions.actions id="charge-type" name="Charge Type" />
                                        <div class="table-responsive">
                                            <table class="table mb-0" id="charge-type">
                                                <thead>
                                                    <tr>
                                                    <th>Charge Type</th>
                                                    @foreach ($chargestypemodule as $item)
                                                    <th>
                                                      {{$item->charge_type}}
                                                    </th>                                                    
                                                    @endforeach                                                  
                                                    <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tbody>
                                                @foreach ($chargetypes as $chargetype)
                                                    <tr>
                                                        <td>{{ $chargetype->charge_type }}</td>

                                                        @foreach ($chargestypemodule as $module)
                                                            <td>
                                                                <input type="checkbox"
                                                                       name="charges[{{ $chargetype->id }}][{{ $module->module_shortcode }}]"
                                                                       value="1"
                                                                       onclick="recordUpdate(this,'{{$module->module_shortcode}}',{{ $chargetype->id }})"
                                                                       @if (in_array($module->module_shortcode, $filter[$chargetype->id] ?? [])) checked @endif
                                                                >
                                                            </td>
                                                        @endforeach
                                                        <td>
                                                            <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill" 
                                                            onclick="edit_type({{$chargetype->id}},'{{  $chargetype->charge_type }}')"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#edit_type">
                                                            <i class="ti ti-pencil"></i></button>
                                                            <form class="d-inline" action="{{ route('charge_type_module.destroy') }}" method="POST">
                                                                @csrf
                                                                @method('delete')
                                                                <input type="hidden" name="id" value="{{ $chargetype->id }}">
                                                                <button onclick="return confirm('Are you sure?')"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"><i
                                                                    class="ti ti-trash"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div> <!-- end card-body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header rounded-0"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="modal-title" id="addSpecializationLabel">Add Charge
                    Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('charge_type_module.store') }}" method="POST">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-md-12 border-bottom pb-3">
                            <label for="" class="form-label">Charge Type <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="charge_type" id="charge_type"
                                class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label for="" class="form-label">Module <span
                                    class="text-danger">*</span></label>
                            @foreach ($chargestypemodule as $item)
                                 <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="module[]" id="module{{ $item->id }}" class="form-check-input mt-0" value="{{ $item->module_shortcode }}">
                                <label for="module{{ $item->id }}" class="form-check-label mb-0">{{ $item->charge_type }}</label>
                                </div>
                            @endforeach
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

    <div class="modal fade" id="edit_type" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header rounded-0"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="modal-title" id="addSpecializationLabel">Edit Charge
                    Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('charge_type_module.update') }}" method="POST">
                    @csrf
                    @method('put')
                    <input type="hidden" name="id">
                    <div class="row gy-3">
                        <div class="col-md-12 border-bottom pb-3">
                            <label for="" class="form-label">Charge Type <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="charge_type" id="edit_charge_type"
                                class="form-control" required>
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
        function edit_type(id,charge_type){
            let modal = document.getElementById("edit_type");
            modal.querySelector("input[name='id']").value = id;
            modal.querySelector("input[name='charge_type']").value = charge_type;
        }
        function recordUpdate(target, shortcode, chargeTypeId) {
            const isChecked = target.checked;
        // Show loader
        document.getElementById('loader').style.display = 'block';

            fetch('{{ route('updateChargeTypeModule') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    charge_type_master_id: chargeTypeId,
                    module_shortcode: shortcode,
                    checked: isChecked
                })
            })
            .then(response => response.json())
            .then(data => {
                 // Hide loader
                 document.getElementById('loader').style.display = 'none';
                if (data.success) {
                    console.log('Update successful');
                    showToast('Updated successfully');
                } else {
                    alert('Error updating record');
                    // Optionally revert checkbox state
                    target.checked = !isChecked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('loader').style.display = 'none';
                alert('AJAX error');
                // Revert checkbox state
                target.checked = !isChecked;
            });
        }
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-message');

            toastMsg.innerText = message;
            toast.style.display = 'block';

            setTimeout(() => {
                toast.style.display = 'none';
            }, 2000); // hide after 2 seconds
        }
    </script>
@endsection