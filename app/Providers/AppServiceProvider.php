<?php

namespace App\Providers;

use App\Models\CompanyUserRole;
use App\Models\OfficeUser;
use App\Models\OfficeUserRole;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->share('CompanyUserRole', CompanyUserRole::all());
        view()->share('OfficeUser', OfficeUserRole::all());

    }
}
