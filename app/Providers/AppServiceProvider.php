<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(
            'App\Repositories\ImpresoraRepositoryInterface',
            'App\Repositories\ImpresoraRepository'
        );
        $this->app->bind(
            'App\Repositories\ItemRepositoryInterface',
            'App\Repositories\ItemRepository'
        );
        $this->app->bind(
            'App\Repositories\DependenciaRepositoryInterface',
            'App\Repositories\DependenciaRepository'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
