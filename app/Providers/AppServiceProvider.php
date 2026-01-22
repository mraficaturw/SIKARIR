<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\Paginator;
use App\Models\Vacancy;
use App\Models\Company;
use App\Observers\VacancyObserver;
use App\Observers\CompanyObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register Observers untuk Automatic Cache Invalidation
        Vacancy::observe(VacancyObserver::class);
        Company::observe(CompanyObserver::class);

        // Gunakan Bootstrap 5 untuk pagination
        Paginator::useBootstrapFive();

        // Force HTTPS jika production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Tambahan — jika Vercel pakai proxy
        if (Request::server('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
        }
    }
}
