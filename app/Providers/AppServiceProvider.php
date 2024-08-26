<?php

namespace App\Providers;

use App\Repositories\Contracts\ProjectRepository;
use App\Repositories\Contracts\TaskRepository;
use App\Repositories\ProjectRepositoryEloquent;
use App\Repositories\TaskRepositoryEloquent;
use App\Services\Contracts\PdfService;
use App\Services\DomPdfService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * CANDIDATO: Aqui eu estou registrando as instâncias das interfaces que eu criei na aplicação.
     * Essas interfaces são instanciadas de forma diferente nos testes automatizados. É aqui
     * que habita a beleza da injeção de dependência.
     */
    public function register(): void
    {
        $this->app->instance(ProjectRepository::class, new ProjectRepositoryEloquent);
        $this->app->instance(TaskRepository::class, new TaskRepositoryEloquent);
        $this->app->instance(PdfService::class, new DomPdfService);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
