<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once app_path() . '/Helpers/Helpers.php';
    }
}
