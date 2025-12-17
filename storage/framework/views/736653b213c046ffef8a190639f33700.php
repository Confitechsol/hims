<?php $__env->startSection('content'); ?>

 <!-- row start -->
<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>TPA Management</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <?php if($errors->any()): ?>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($error); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    <?php if(session('error')): ?>
                                        <div class="alert alert-danger">
                                            <?php echo e(session('error')); ?>

                                        </div>
                                    <?php endif; ?>
                                    <?php if(session('success')): ?>
                                        <div class="alert alert-success">
                                            <?php echo e(session('success')); ?>

                                        </div>
                                    <?php endif; ?>
                                <div
                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="input-icon-start position-relative me-2">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-search"></i>
                                                    </span>
                                                    <input onkeyup="dataSearch()" type="text" id="language-search" name="search"
                                                         class="form-control shadow-sm"
                                                        placeholder="Search">
                                                </div>
                                               
                                            </div>
                
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <div class="text-end d-flex">
                                            <a href="javascript:void(0);"
                                                class="btn btn-primary text-white ms-2 btn-md"
                                                data-bs-toggle="modal" data-bs-target="#add_tpa"><i
                                                    class="ti ti-plus me-1"></i>Add TPA</a>
                                        </div>
                                        <!-- First Modal -->
                                        
                                    </div>

                                </div>
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table" id="table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Contact Person Name</th>
                                                <th>Contact Person Phone</th>
                                                <th>Poilicy No</th>
                                                <th>E Card no</th>
                                                <th>E Card Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $organisations->sortByDesc('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($item->organisation_name); ?></td>
                                                <td><?php echo e($item->code); ?></td>   
                                                <td><?php echo e($item->contact_no); ?></td>
                                                <td><?php echo e($item->address); ?></td>
                                                <td><?php echo e($item->contact_person_name); ?></td>
                                                <td><?php echo e($item->contact_person_phone); ?></td>
                                                <td><?php echo e($item->poilicy_no); ?></td>
                                                <td><?php echo e($item->e_card_no); ?></td>
                                                <td>
                                                    <?php if($item->e_card_upload): ?>
                                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ecardModal<?php echo e($item->id); ?>">
                                                            <img src="<?php echo e(asset($item->e_card_upload)); ?>" alt="E Card" width="100" style="cursor:pointer" class="img-thumbnail">
                                                        </a>

                                                        <div class="modal fade" id="ecardModal<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="ecardModalLabel<?php echo e($item->id); ?>" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="ecardModalLabel<?php echo e($item->id); ?>">E Card - <?php echo e($item->organisation_name); ?></h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <img src="<?php echo e(asset($item->e_card_upload)); ?>" alt="E Card" class="img-fluid">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="<?php echo e(route('tpa_details.show', $item->id)); ?>"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                title="Show"></i></a>
                                                        <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="<?php echo e($item["id"]); ?>"
                                                            data-organisation_name="<?php echo e($item->organisation_name); ?>"
                                                            data-code="<?php echo e($item->code); ?>"
                                                            data-contact_no="<?php echo e($item->contact_no); ?>"
                                                            data-address="<?php echo e($item->address); ?>"
                                                            data-contact_person_name="<?php echo e($item->contact_person_name); ?>"
                                                            data-contact_person_phone="<?php echo e($item->contact_person_phone); ?>"
                                                            data-poilicy_no="<?php echo e($item->poilicy_no); ?>"
                                                            data-e_card_no="<?php echo e($item->e_card_no); ?>"
                                                            data-e_card_image="/<?php echo e($item->e_card_upload); ?>"
                                                            data-e_card_upload="">
                                                            <i class="ti ti-pencil"></i>
                                                        </button>
                                                        <form method="POST" action="<?php echo e(route('tpamanagement.destroy')); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <input type="hidden" name="id" value="<?php echo e($item['id']); ?>">
                                                            <button type="submit"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                                onclick="return confirm('Are you sure you want to delete this item?');">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['type' => 'add','id' => 'add_tpa','title' => 'Add TPA','action' => ''.e(route('tpamanagement.store')).'','fields' => [
        ['name' => 'organisation_name', 'label' => 'organisation Name', 'type' => 'text', 'required' => true,'size'=>'5'],
        ['name' => 'code', 'label' => 'Code', 'type' => 'text', 'required' => true,'size'=>'3'],
        ['name' => 'contact_no', 'label' => 'Phone', 'type' => 'text', 'required' => true,'size'=>'4'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'text', 'required' => true,'size'=>'12'],
        ['name' => 'contact_person_name', 'label' => 'Contact Person Name', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'contact_person_phone', 'label' => 'Contact Person Phone', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'poilicy_no', 'label' => 'Poilicy No', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'e_card_no', 'label' => 'E Card No', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'e_card_upload', 'label' => 'E Card Upload', 'type' => 'file', 'required' => true,'size'=>'12'],
        ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'add','id' => 'add_tpa','title' => 'Add TPA','action' => ''.e(route('tpamanagement.store')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'organisation_name', 'label' => 'organisation Name', 'type' => 'text', 'required' => true,'size'=>'5'],
        ['name' => 'code', 'label' => 'Code', 'type' => 'text', 'required' => true,'size'=>'3'],
        ['name' => 'contact_no', 'label' => 'Phone', 'type' => 'text', 'required' => true,'size'=>'4'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'text', 'required' => true,'size'=>'12'],
        ['name' => 'contact_person_name', 'label' => 'Contact Person Name', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'contact_person_phone', 'label' => 'Contact Person Phone', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'poilicy_no', 'label' => 'Poilicy No', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'e_card_no', 'label' => 'E Card No', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'e_card_upload', 'label' => 'E Card Upload', 'type' => 'file', 'required' => true,'size'=>'12'],
        ]),'columns' => 3]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a)): ?>
<?php $attributes = $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a; ?>
<?php unset($__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal66ca70ec79ff22faa62f501a1b49a88a)): ?>
<?php $component = $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a; ?>
<?php unset($__componentOriginal66ca70ec79ff22faa62f501a1b49a88a); ?>
<?php endif; ?>
 <?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Company Name','action' => ''.e(route('tpamanagement.update')).'','fields' => [
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'organisation_name', 'label' => 'organisation Name', 'type' => 'text', 'required' => true,'size'=>'5'],
        ['name' => 'code', 'label' => 'Code', 'type' => 'text', 'required' => true,'size'=>'3'],
        ['name' => 'contact_no', 'label' => 'Phone', 'type' => 'text', 'required' => true,'size'=>'4'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'text', 'required' => true,'size'=>'12'],
        ['name' => 'contact_person_name', 'label' => 'Contact Person Name', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'contact_person_phone', 'label' => 'Contact Person Phone', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'poilicy_no', 'label' => 'Poilicy No', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'e_card_no', 'label' => 'E Card No', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'e_card_image', 'label' => 'E Card Current Image', 'type' => 'img', 'required' => true,'size'=>'12'],
        ['name' => 'e_card_upload', 'label' => 'E Card Image Update', 'type' => 'file', 'required' => false ,'size'=>'12'],
    ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Company Name','action' => ''.e(route('tpamanagement.update')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'organisation_name', 'label' => 'organisation Name', 'type' => 'text', 'required' => true,'size'=>'5'],
        ['name' => 'code', 'label' => 'Code', 'type' => 'text', 'required' => true,'size'=>'3'],
        ['name' => 'contact_no', 'label' => 'Phone', 'type' => 'text', 'required' => true,'size'=>'4'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'text', 'required' => true,'size'=>'12'],
        ['name' => 'contact_person_name', 'label' => 'Contact Person Name', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'contact_person_phone', 'label' => 'Contact Person Phone', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'poilicy_no', 'label' => 'Poilicy No', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'e_card_no', 'label' => 'E Card No', 'type' => 'text', 'required' => true,'size'=>'6'],
        ['name' => 'e_card_image', 'label' => 'E Card Current Image', 'type' => 'img', 'required' => true,'size'=>'12'],
        ['name' => 'e_card_upload', 'label' => 'E Card Image Update', 'type' => 'file', 'required' => false ,'size'=>'12'],
    ]),'columns' => 3]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a)): ?>
<?php $attributes = $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a; ?>
<?php unset($__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal66ca70ec79ff22faa62f501a1b49a88a)): ?>
<?php $component = $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a; ?>
<?php unset($__componentOriginal66ca70ec79ff22faa62f501a1b49a88a); ?>
<?php endif; ?>
    
   
<!-- row end -->

<script>
   function dataSearch(){
    const data=document.querySelector('#language-search');
    let table = document.querySelector("#table");
    
    fetch("<?php echo e(route('tpamanagement')); ?>?search="+encodeURIComponent(data.value))
    .then(res => res.json())
    .then(data => {
        if(data.status == 200){
            table.querySelector("tbody").innerHTML = '';
data.result.forEach((item)=>{
 const row = document.createElement('tr');
 row.innerHTML = `
 <td>${item.organisation_name}</td>
 <td>${item.code}</td>
 <td>${item.contact_no}</td>
 <td>${item.address}</td>
 <td>${item.contact_person_name}</td>
 <td>${item.contact_person_phone}</td>
 <td>
                                                    <div class="d-flex">
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                title="Show"></i></a>
                                                        <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="${item.id}"
                                                            data-organisation_name="${item.organisation_name}"
                                                            data-code="${item.code}"
                                                            data-contact_no="${item.contact_no}"
                                                            data-address="${item.address}"
                                                            data-contact_person_name="${item.contact_person_name}"
                                                            data-contact_person_phone="${item.contact_person_phone}">
                                                            <i class="ti ti-pencil"></i>
                                                        </button>
                                                        <form method="POST" action="<?php echo e(route('tpamanagement.destroy')); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <input type="hidden" name="id" value="<?php echo e($item['id']); ?>">
                                                            <button type="submit"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                                onclick="return confirm('Are you sure you want to delete this item?');">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
 `;

 table.querySelector("tbody").appendChild(row);
});
        }
        
    })
    .catch(err => console.error(err));

   }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\tpa\tpamanagement.blade.php ENDPATH**/ ?>