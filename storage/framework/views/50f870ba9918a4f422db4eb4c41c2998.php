<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-file-import me-2"></i>Import Medicines
                    </h5>
                </div>

                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                    <?php endif; ?>

                    <div class="alert alert-info">
                        <h6 class="alert-heading">CSV File Format</h6>
                        <p class="mb-0">Please ensure your CSV file has the following columns in order:</p>
                        <ol class="mb-0 mt-2">
                            <li>Medicine Name</li>
                            <li>Medicine Category ID</li>
                            <li>Medicine Company ID</li>
                            <li>Medicine Composition</li>
                            <li>Medicine Group ID</li>
                            <li>Unit ID</li>
                            <li>Min Level</li>
                            <li>Reorder Level</li>
                            <li>GST Percentage (5, 12, 18, or 28)</li>
                            <li>Unit Packing</li>
                            <li>Rack Number</li>
                        </ol>
                    </div>

                    <form action="<?php echo e(route('pharmacy.import.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-4">
                            <label class="form-label">Select CSV File <span class="text-danger">*</span></label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                            <small class="text-muted">Accepted formats: .csv, .txt</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('pharmacy.index')); ?>" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-upload me-1"></i>Import Medicines
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="card bg-light">
                        <div class="card-body">
                            <h6>Sample CSV Content:</h6>
                            <pre class="mb-0"><code>Medicine Name,Category ID,Company ID,Composition,Group ID,Unit ID,Min Level,Reorder Level,GST%,Unit Packing,Rack Number
Paracetamol 500mg,2,1,Paracetamol IP 500mg,6,1,20,50,12,10 Tablets,A1
Azithromycin 250mg,1,2,Azithromycin 250mg,1,1,10,30,18,6 Tablets,B2
Life Saving Drug,3,1,Essential Medicine,2,1,15,40,5,15 Tablets,C3</code></pre>
                            <small class="text-muted d-block mt-2">
                                <strong>Common GST Rates:</strong> 
                                Life-saving drugs: 5% | Essential medicines: 12% | General medicines: 18% | Non-essential: 28%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\pharmacy\import.blade.php ENDPATH**/ ?>