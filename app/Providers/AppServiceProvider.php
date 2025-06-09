<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Console\Commands\RepositoryGenerator;
use App\Console\Commands\ServiceGenerator;
use Illuminate\Support\ServiceProvider;
use L5Swagger\L5SwaggerServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        JsonResource::withoutWrapping();

        $this->app->register(L5SwaggerServiceProvider::class);

        $this->commands([
            RepositoryGenerator::class,
            ServiceGenerator::class
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
