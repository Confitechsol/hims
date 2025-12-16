<!DOCTYPE html>
<html>
<head>
    <title>Test Purchase Create</title>
</head>
<body>
    <h1>Purchase Create Test</h1>
    
    <h2>1. Categories Check:</h2>
    <?php if(isset($categories)): ?>
        <p>✅ Categories variable exists: <?php echo e($categories->count()); ?> categories</p>
        <ul>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>ID: <?php echo e($cat->id); ?> - Name: <?php echo e($cat->medicine_category); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php else: ?>
        <p>❌ Categories not set</p>
    <?php endif; ?>

    <h2>2. Suppliers Check:</h2>
    <?php if(isset($suppliers)): ?>
        <p>✅ Suppliers variable exists: <?php echo e($suppliers->count()); ?> suppliers</p>
        <ul>
            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>ID: <?php echo e($sup->id); ?> - Name: <?php echo e($sup->supplier); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php else: ?>
        <p>❌ Suppliers not set</p>
    <?php endif; ?>

    <h2>3. Category Dropdown Test:</h2>
    <select id="test-category" class="medicine-category">
        <option value="">Select Category</option>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($category->id); ?>"><?php echo e($category->medicine_category); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <h2>4. Medicine Dropdown Test:</h2>
    <select id="test-medicine" class="medicine-name">
        <option value="">Select Medicine</option>
    </select>

    <script>
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('medicine-category')) {
            alert('Category changed to: ' + e.target.value);
            const categoryId = e.target.value;
            const medicineSelect = document.getElementById('test-medicine');
            
            if (categoryId) {
                const apiUrl = `<?php echo e(route('pharmacy.purchase.api.medicines-by-category')); ?>?category_id=${categoryId}`;
                alert('Fetching from: ' + apiUrl);
                
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(medicines => {
                        alert('Got ' + medicines.length + ' medicines');
                        medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
                        medicines.forEach(medicine => {
                            const option = document.createElement('option');
                            option.value = medicine.id;
                            option.textContent = medicine.medicine_name;
                            medicineSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                    });
            }
        }
    });
    </script>
</body>
</html>

<?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\pharmacy\purchase\test.blade.php ENDPATH**/ ?>