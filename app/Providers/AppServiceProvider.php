<?php

namespace App\Providers;

use App\Repositories\Contracts\ProjectRepository;
use App\Repositories\ProjectRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(ProjectRepository::class, new ProjectRepositoryEloquent);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
