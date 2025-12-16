<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #750096">
                            <i class="fas fa-file-invoice me-2"></i>Purchase Order Details
                        </h5>
                        <div>
                            <a href="<?php echo e(route('pharmacy.purchase.edit', $purchase->id)); ?>" class="btn btn-sm btn-warning text-white">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                            <a href="<?php echo e(route('pharmacy.purchase.print', $purchase->id)); ?>" target="_blank" class="btn btn-sm btn-info text-white">
                                <i class="ti ti-printer me-1"></i>Print
                            </a>
                            <a href="<?php echo e(route('pharmacy.purchase.index')); ?>" class="btn btn-sm btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Purchase No:</th>
                                    <td><span class="badge bg-primary">PHPN<?php echo e($purchase->id); ?></span></td>
                                </tr>
                                <tr>
                                    <th>Purchase Date:</th>
                                    <td><?php echo e(\Carbon\Carbon::parse($purchase->date)->format('d M Y, h:i A')); ?></td>
                                </tr>
                                <tr>
                                    <th>Invoice No:</th>
                                    <td><?php echo e($purchase->invoice_no ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Supplier:</th>
                                    <td><?php echo e($purchase->supplier->supplier ?? '-'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Payment Mode:</th>
                                    <td><?php echo e($purchase->payment_mode ?? '-'); ?></td>
                                </tr>
                                <?php if($purchase->payment_mode === 'Cheque'): ?>
                                <tr>
                                    <th>Cheque No:</th>
                                    <td><?php echo e($purchase->cheque_no ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Cheque Date:</th>
                                    <td><?php echo e($purchase->cheque_date ? \Carbon\Carbon::parse($purchase->cheque_date)->format('d M Y') : '-'); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Received By:</th>
                                    <td><?php echo e($purchase->receivedBy->name ?? '-'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Medicine Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th>Batch No</th>
                                            <th>Expiry Date</th>
                                            <th>Quantity</th>
                                            <th>Purchase Price</th>
                                            <th>MRP</th>
                                            <th>Sale Price</th>
                                            <th>Tax</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $purchase->batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($batch->pharmacy->medicine_name ?? '-'); ?></td>
                                                <td><?php echo e($batch->batch_no); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($batch->expiry)->format('M Y')); ?></td>
                                                <td><?php echo e($batch->quantity); ?></td>
                                                <td>₹<?php echo e(number_format($batch->purchase_price, 2)); ?></td>
                                                <td>₹<?php echo e(number_format($batch->mrp, 2)); ?></td>
                                                <td>₹<?php echo e(number_format($batch->sale_rate, 2)); ?></td>
                                                <td><?php echo e(number_format($batch->tax, 2)); ?>%</td>
                                                <td>₹<?php echo e(number_format($batch->amount, 2)); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-6">
                            <?php if($purchase->note): ?>
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Note</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0"><?php echo e($purchase->note); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if($purchase->payment_note): ?>
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Payment Note</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0"><?php echo e($purchase->payment_note); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Financial Summary</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <th>Total:</th>
                                            <td class="text-end">₹<?php echo e(number_format($purchase->total, 2)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tax:</th>
                                            <td class="text-end">₹<?php echo e(number_format($purchase->tax, 2)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Discount:</th>
                                            <td class="text-end">₹<?php echo e(number_format($purchase->discount, 2)); ?></td>
                                        </tr>
                                        <tr class="border-top">
                                            <th class="fs-5">Net Amount:</th>
                                            <td class="text-end fs-5 fw-bold text-primary">₹<?php echo e(number_format($purchase->net_amount, 2)); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($purchase->attachment): ?>
                    <div class="mt-3">
                        <a href="<?php echo e(asset('storage/' . $purchase->attachment)); ?>" class="btn btn-sm btn-outline-primary" download>
                            <i class="ti ti-download me-1"></i>Download Attachment (<?php echo e($purchase->attachment_name); ?>)
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\pharmacy\purchase\show.blade.php ENDPATH**/ ?>