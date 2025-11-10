<<<<<<< HEAD
<div class="hidden overflow-x-auto sm:col-span-1 lg:block">
    <div
        class="h-[35.5rem] scrollbar-hidden trace text-sm text-gray-400 dark:text-gray-300"
    >
        <div class="mb-2 inline-block rounded-full bg-red-500/20 px-3 py-2 dark:bg-red-500/20 sm:col-span-1">
            <button
                @click="includeVendorFrames = !includeVendorFrames"
                class="inline-flex items-center font-bold leading-5 text-red-500"
            >
                <span x-show="includeVendorFrames">Collapse</span>
                <span
                    x-cloak
                    x-show="!includeVendorFrames"
                    >Expand</span
                >
                <span class="ml-1">vendor frames</span>

                <div class="flex flex-col ml-1 -mt-2" x-cloak x-show="includeVendorFrames">
                    <?php if (isset($component)) { $__componentOriginal707ceba27255eae48fdb0f3529710ddf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal707ceba27255eae48fdb0f3529710ddf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'laravel-exceptions-renderer::components.icons.chevron-down','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('laravel-exceptions-renderer::icons.chevron-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal707ceba27255eae48fdb0f3529710ddf)): ?>
<?php $attributes = $__attributesOriginal707ceba27255eae48fdb0f3529710ddf; ?>
<?php unset($__attributesOriginal707ceba27255eae48fdb0f3529710ddf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal707ceba27255eae48fdb0f3529710ddf)): ?>
<?php $component = $__componentOriginal707ceba27255eae48fdb0f3529710ddf; ?>
<?php unset($__componentOriginal707ceba27255eae48fdb0f3529710ddf); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal14b1cc5db95fcca4a0f06445821cff39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14b1cc5db95fcca4a0f06445821cff39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'laravel-exceptions-renderer::components.icons.chevron-up','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('laravel-exceptions-renderer::icons.chevron-up'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14b1cc5db95fcca4a0f06445821cff39)): ?>
<?php $attributes = $__attributesOriginal14b1cc5db95fcca4a0f06445821cff39; ?>
<?php unset($__attributesOriginal14b1cc5db95fcca4a0f06445821cff39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14b1cc5db95fcca4a0f06445821cff39)): ?>
<?php $component = $__componentOriginal14b1cc5db95fcca4a0f06445821cff39; ?>
<?php unset($__componentOriginal14b1cc5db95fcca4a0f06445821cff39); ?>
<?php endif; ?>
                </div>

                <div class="flex flex-col ml-1 -mt-2" x-cloak x-show="! includeVendorFrames">
                    <?php if (isset($component)) { $__componentOriginal14b1cc5db95fcca4a0f06445821cff39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14b1cc5db95fcca4a0f06445821cff39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'laravel-exceptions-renderer::components.icons.chevron-up','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('laravel-exceptions-renderer::icons.chevron-up'); ?>
=======
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['exception']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['exception']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="flex flex-col gap-2.5 bg-neutral-50 dark:bg-white/1 border border-neutral-200 dark:border-neutral-800 rounded-xl p-2.5 shadow-xs">
    <div class="flex items-center gap-2.5 p-2">
        <div class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-white/5 rounded-md w-6 h-6 flex items-center justify-center p-1">
            <?php if (isset($component)) { $__componentOriginalebc8ec9a834a8051f56913d6745a7050 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalebc8ec9a834a8051f56913d6745a7050 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'laravel-exceptions-renderer::components.icons.alert','data' => ['class' => 'w-2.5 h-2.5 text-blue-500 dark:text-emerald-500']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('laravel-exceptions-renderer::icons.alert'); ?>
>>>>>>> 53ad2ac1640d46f4ef6740dd073a2ce3bbdd7ea7
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<<<<<<< HEAD
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14b1cc5db95fcca4a0f06445821cff39)): ?>
<?php $attributes = $__attributesOriginal14b1cc5db95fcca4a0f06445821cff39; ?>
<?php unset($__attributesOriginal14b1cc5db95fcca4a0f06445821cff39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14b1cc5db95fcca4a0f06445821cff39)): ?>
<?php $component = $__componentOriginal14b1cc5db95fcca4a0f06445821cff39; ?>
<?php unset($__componentOriginal14b1cc5db95fcca4a0f06445821cff39); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal707ceba27255eae48fdb0f3529710ddf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal707ceba27255eae48fdb0f3529710ddf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'laravel-exceptions-renderer::components.icons.chevron-down','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('laravel-exceptions-renderer::icons.chevron-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal707ceba27255eae48fdb0f3529710ddf)): ?>
<?php $attributes = $__attributesOriginal707ceba27255eae48fdb0f3529710ddf; ?>
<?php unset($__attributesOriginal707ceba27255eae48fdb0f3529710ddf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal707ceba27255eae48fdb0f3529710ddf)): ?>
<?php $component = $__componentOriginal707ceba27255eae48fdb0f3529710ddf; ?>
<?php unset($__componentOriginal707ceba27255eae48fdb0f3529710ddf); ?>
<?php endif; ?>
                </div>
            </button>
        </div>

        <div class="mb-12 space-y-2">
            <?php $__currentLoopData = $exception->frames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $frame): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(! $frame->isFromVendor()): ?>
                    <?php
                        $vendorFramesCollapsed = $exception->frames()->take($loop->index)->reverse()->takeUntil(fn ($frame) => ! $frame->isFromVendor());
                    ?>

                    <div x-show="! includeVendorFrames">
                        <?php if($vendorFramesCollapsed->isNotEmpty()): ?>
                            <div class="text-gray-500">
                                <?php echo e($vendorFramesCollapsed->count()); ?> vendor frame<?php echo e($vendorFramesCollapsed->count() > 1 ? 's' : ''); ?> collapsed
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <button
                    class="w-full text-left dark:border-gray-900"
                    x-show="<?php echo e($frame->isFromVendor() ? 'includeVendorFrames' : 'true'); ?>"
                    @click="index = <?php echo e($loop->index); ?>"
                >
                    <div
                        x-bind:class="
                            index === <?php echo e($loop->index); ?>

                                ? 'rounded-r-md bg-gray-100 dark:bg-gray-800 border-l dark:border dark:border-gray-700 border-l-red-500 dark:border-l-red-500'
                                : 'hover:bg-gray-100/75 dark:hover:bg-gray-800/75'
                        "
                    >
                        <div class="scrollbar-hidden overflow-x-auto border-l-2 border-transparent p-2">
                            <div class="nowrap text-gray-900 dark:text-gray-300">
                                <span class="inline-flex items-baseline">
                                    <span class="text-gray-900 dark:text-gray-300"><?php echo e($frame->source()); ?></span>
                                    <span class="font-mono text-xs">:<?php echo e($frame->line()); ?></span>
                                </span>
                            </div>
                            <div class="text-gray-500 dark:text-gray-400">
                                <?php echo e($exception->frames()->get($loop->index + 1)?->callable()); ?>

                            </div>
                        </div>
                    </div>
                </button>

                <?php if(! $frame->isFromVendor() && $exception->frames()->slice($loop->index + 1)->reject(fn ($frame) => $frame->isFromVendor())->isEmpty()): ?>
                    <?php if($exception->frames()->slice($loop->index + 1)->count()): ?>
                        <div x-show="! includeVendorFrames">
                            <div class="text-gray-500">
                                <?php echo e($exception->frames()->slice($loop->index + 1)->count()); ?> vendor
                                frame<?php echo e($exception->frames()->slice($loop->index + 1)->count() > 1 ? 's' : ''); ?> collapsed
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
=======
<?php $component->withAttributes(['class' => 'w-2.5 h-2.5 text-blue-500 dark:text-emerald-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalebc8ec9a834a8051f56913d6745a7050)): ?>
<?php $attributes = $__attributesOriginalebc8ec9a834a8051f56913d6745a7050; ?>
<?php unset($__attributesOriginalebc8ec9a834a8051f56913d6745a7050); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalebc8ec9a834a8051f56913d6745a7050)): ?>
<?php $component = $__componentOriginalebc8ec9a834a8051f56913d6745a7050; ?>
<?php unset($__componentOriginalebc8ec9a834a8051f56913d6745a7050); ?>
<?php endif; ?>
        </div>
        <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Exception trace</h3>
    </div>

    <div class="flex flex-col gap-1.5">
        <?php $__currentLoopData = $exception->frameGroups(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($group['is_vendor']): ?>
                <?php if (isset($component)) { $__componentOriginal449787012edfba29f0e80f325065fad5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal449787012edfba29f0e80f325065fad5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'laravel-exceptions-renderer::components.vendor-frames','data' => ['frames' => $group['frames']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('laravel-exceptions-renderer::vendor-frames'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['frames' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($group['frames'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal449787012edfba29f0e80f325065fad5)): ?>
<?php $attributes = $__attributesOriginal449787012edfba29f0e80f325065fad5; ?>
<?php unset($__attributesOriginal449787012edfba29f0e80f325065fad5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal449787012edfba29f0e80f325065fad5)): ?>
<?php $component = $__componentOriginal449787012edfba29f0e80f325065fad5; ?>
<?php unset($__componentOriginal449787012edfba29f0e80f325065fad5); ?>
<?php endif; ?>
            <?php else: ?>
                <?php $__currentLoopData = $group['frames']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $frame): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginalc7c58c6d16fe849872fb25ad6e9b8407 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7c58c6d16fe849872fb25ad6e9b8407 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'laravel-exceptions-renderer::components.frame','data' => ['frame' => $frame]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('laravel-exceptions-renderer::frame'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['frame' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($frame)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7c58c6d16fe849872fb25ad6e9b8407)): ?>
<?php $attributes = $__attributesOriginalc7c58c6d16fe849872fb25ad6e9b8407; ?>
<?php unset($__attributesOriginalc7c58c6d16fe849872fb25ad6e9b8407); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7c58c6d16fe849872fb25ad6e9b8407)): ?>
<?php $component = $__componentOriginalc7c58c6d16fe849872fb25ad6e9b8407; ?>
<?php unset($__componentOriginalc7c58c6d16fe849872fb25ad6e9b8407); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
>>>>>>> 53ad2ac1640d46f4ef6740dd073a2ce3bbdd7ea7
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\hims\vendor\laravel\framework\src\Illuminate\Foundation\Providers/../resources/exceptions/renderer/components/trace.blade.php ENDPATH**/ ?>