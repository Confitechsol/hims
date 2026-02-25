<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Hospital;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\BloodGroupComposer;

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
        // Force URL generation to include /admin prefix
         URL::forceRootUrl(config('app.url'));
        
        // // If behind a proxy
        if ($this->app->environment('production')) {
            URL::forceScheme('http');
        }
        View::composer(
            'components.modals.add-patients-modal',
            BloodGroupComposer::class
        );
        // ✅ Share Hospital Data Globally (for sidebar logo)
        View::composer('*', function ($view) {
            $hospital = Hospital::first(); // change if multi-hospital
            $view->with('hospitalData', $hospital);
        });
    }
}
