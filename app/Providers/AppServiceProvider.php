<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Hospital;
use App\Models\Area;
use App\Models\BloodBankProduct; 
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\BloodGroupComposer;
use App\Http\View\Composers\AreaComposer;
use App\Models\Bed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    URL::forceRootUrl(config('app.url'));

    if ($this->app->environment('production')) {
        URL::forceScheme('http');
    }

    // ✅ Single Composer for Modal (BloodGroup + Area together)
    View::composer('components.modals.add-patients-modal', function ($view) {

        $bloodGroups = BloodBankProduct::all();
        $areas = Area::all();

        $view->with([
            'bloodGroups' => $bloodGroups,
            'areas' => $areas,
        ]);
    });
    View::composer('components.modals.bed-modal', function ($view) {

        $beds = Bed::with([
            'bedGroup:id,name,floor',
            'activePatient'
        ])->get();

        $grouped = [];

        foreach ($beds as $bed) {
            $floor = $bed->bedGroup->floor ?? 'Unknown';
            $groupName = $bed->bedGroup->name ?? 'General';
        
            $active = $bed->activePatient;
        
            $isOccupied = $active ? true : false;
            $patientName = $active?->ipd?->patient?->patient_name;
        
            $grouped[$floor][$groupName][] = [
                'id' => $bed->id,
                'name' => $bed->name,
                'is_occupied' => $isOccupied,
                'patient_name' => $patientName,
            ];
        }
        $view->with([
            'grouped' => $grouped,
        ]);
    });
    // ✅ Share Hospital Data Globally
    View::composer('*', function ($view) {
        $hospital = Hospital::first();
        $view->with('hospitalData', $hospital);
    });
}
}
