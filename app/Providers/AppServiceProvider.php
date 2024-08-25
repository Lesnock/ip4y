<?php

namespace App\Providers;

use App\Repositories\Contracts\ProjectRepository;
use App\Repositories\Contracts\TaskRepository;
use App\Repositories\ProjectRepositoryEloquent;
use App\Repositories\TaskRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(ProjectRepository::class, new ProjectRepositoryEloquent);
        $this->app->instance(TaskRepository::class, new TaskRepositoryEloquent);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
