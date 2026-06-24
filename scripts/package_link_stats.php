<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$ins = App\Models\Package::where('package_type', 'insurance')->count();
$linked = App\Models\Package::where('package_type', 'insurance')->whereNotNull('linked_hospital_package_id')->count();
echo "Insurance packages: $ins\n";
echo "Linked to hospital: $linked\n\n";

App\Models\Package::where('package_type', 'insurance')
    ->whereNotNull('linked_hospital_package_id')
    ->with('linkedHospitalPackage:id,name')
    ->get(['id', 'name', 'insurer_procedure_code', 'linked_hospital_package_id'])
    ->each(function ($p) {
        echo "{$p->insurer_procedure_code} | {$p->name} => {$p->linkedHospitalPackage?->name}\n";
    });

echo "\nSample hospital packages (TONSIL/APPEND/CHOLE):\n";
App\Models\Package::where(function ($q) {
    $q->where('package_type', 'hospital')->orWhereNull('package_type');
})
    ->where(function ($q) {
        $q->where('name', 'like', '%TONSIL%')
            ->orWhere('name', 'like', '%APPEND%')
            ->orWhere('name', 'like', '%CHOLE%')
            ->orWhere('name', 'like', '%ANGIO%');
    })
    ->limit(20)
    ->pluck('name')
    ->each(fn ($n) => print("  - $n\n"));
