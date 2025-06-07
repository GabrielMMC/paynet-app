<?php

namespace App\Providers;

use App\Console\Commands\RepositoryGenerator;
use App\Console\Commands\ServiceGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
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
