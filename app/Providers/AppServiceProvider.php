<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use PDO;

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
        //Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        if (DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
            DB::connection()->getPdo()->sqliteCreateFunction('REGEXP', function ($pattern, $value) {
                mb_regex_encoding('UTF-8');
                return (bool) preg_match('/' . $pattern . '/', $value);
            });
        }
    }
}
