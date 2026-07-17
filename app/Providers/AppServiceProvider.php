<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Hospital;
use App\Models\Area;
use App\Models\BloodBankProduct;
use App\Models\InsuranceCompany;
use App\Models\Organisation;
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
        // Ensure permission helpers are always available (even if Composer files
        // autoload was not regenerated on the server).
        $helper = app_path('Helpers/PermissionHelper.php');
        if (is_file($helper)) {
            require_once $helper;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    $this->registerOrganisationInsuranceRelations();

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

        // Use patientBedHistory (always on Bed) with a constraint — avoids requiring
        // the activePatient() relationship, which may be missing on older deployments.
        $beds = Bed::with([
            'bedGroup:id,name,floor',
            'patientBedHistory' => function ($query) {
                $query->where('is_active', 'yes')
                    ->with('ipd.patient:id,patient_name');
            },
        ])->get();

        $grouped = [];

        foreach ($beds as $bed) {
            $floor = $bed->bedGroup->floor ?? 'Unknown';
            $groupName = $bed->bedGroup->name ?? 'General';

            $active = $bed->patientBedHistory->first();

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

    /**
     * Ensure insurance relations exist on Organisation even if an older model
     * file was deployed on Linux (case-sensitive) without these methods.
     */
    protected function registerOrganisationInsuranceRelations(): void
    {
        Organisation::resolveRelationUsing('insuranceCompany', function (Organisation $organisation) {
            return $organisation->belongsTo(InsuranceCompany::class, 'insurance_company_id');
        });

        Organisation::resolveRelationUsing('insuranceCompanies', function (Organisation $organisation) {
            return $organisation->belongsToMany(
                InsuranceCompany::class,
                'insurance_company_organisation',
                'organisation_id',
                'insurance_company_id'
            )->withTimestamps();
        });
    }
}
