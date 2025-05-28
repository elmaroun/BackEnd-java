<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\EloquentAvisRepository;
use App\Repositories\RepositoryInterface;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
        {
            $this->app->bind(
                RepositoryInterface::class,
                EloquentAvisRepository::class
            );
        }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
