<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        
        // If behind a proxy
        if ($this->app->environment('production')) {
            URL::forceScheme('http');
        }
    }
}
