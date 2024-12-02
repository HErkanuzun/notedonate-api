<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginViewResponse;
use App\Http\Responses\CustomLoginViewResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind custom login view response if needed
        $this->app->singleton(LoginViewResponse::class, CustomLoginViewResponse::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Define Fortify views if needed
        Fortify::loginView(function () {
            return view('auth.login');
        });
    }
}
