<?php

namespace App\Providers;

use App\Services\Contracts\UserService as UserServiceContract;
use App\Services\UserService;
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
        $this->app->bind(UserServiceContract::class, UserService::class);
    }
}
