<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Scramble;
use Illuminate\Routing\Route;

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
        $scramble = Scramble::routes(function (Route $route) {
            return $route->middleware('api') &&
                $route->getPrefix() === 'api';
        });

        if ($scramble) {
            $scramble->info([
                'title' => 'Disperkim API',
                'description' => 'REST API Documentation untuk Sistem Manajemen Sampah Disperkim',
                'version' => '1.0.0',
            ])->ui(\Dedoc\Scramble\UI\ScrambleUI::class);
        }
    }
}

