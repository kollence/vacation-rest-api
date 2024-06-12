<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\User;
use App\Models\VacationRequest;
use App\Policies\AdminPolicy;
use App\Policies\TeamPolicy;
use App\Policies\VacationRequestPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(VacationRequest::class, VacationRequestPolicy::class);
        Gate::policy(Team::class, TeamPolicy::class);
        Gate::policy('*', AdminPolicy::class);
    }
}
