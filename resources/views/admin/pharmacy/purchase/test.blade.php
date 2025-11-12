<!DOCTYPE html>
<html>
<head>
    <title>Test Purchase Create</title>
</head>
<body>
    <h1>Purchase Create Test</h1>
    
    <h2>1. Categories Check:</h2>
    @if(isset($categories))
        <p>✅ Categories variable exists: {{ $categories->count() }} categories</p>
        <ul>
            @foreach($categories as $cat)
                <li>ID: {{ $cat->id }} - Name: {{ $cat->medicine_category }}</li>
            @endforeach
        </ul>
    @else
        <p>❌ Categories not set</p>
    @endif

    <h2>2. Suppliers Check:</h2>
    @if(isset($suppliers))
        <p>✅ Suppliers variable exists: {{ $suppliers->count() }} suppliers</p>
        <ul>
            @foreach($suppliers as $sup)
                <li>ID: {{ $sup->id }} - Name: {{ $sup->supplier }}</li>
            @endforeach
        </ul>
    @else
        <p>❌ Suppliers not set</p>
    @endif

    <h2>3. Category Dropdown Test:</h2>
    <select id="test-category" class="medicine-category">
        <option value="">Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->medicine_category }}</option>
        @endforeach
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
                const apiUrl = `{{ route('pharmacy.purchase.api.medicines-by-category') }}?category_id=${categoryId}`;
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

