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

    // ✅ Share Hospital Data Globally
    View::composer('*', function ($view) {
        $hospital = Hospital::first();
        $view->with('hospitalData', $hospital);
    });
}
}
