<?php

namespace Karellens\ReactiveAdmin;

use Illuminate\Support\ServiceProvider;

class ReactiveAdminServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'reactive-admin');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'reactive-admin');

        $this->publishes([__DIR__.'/../config/reactiveadmin.php' => config_path('reactiveadmin.php')], 'reactive-admin-config');
        $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/reactive-admin')], 'reactive-admin-assets');

        $this->loadRoutesFrom('routes.php');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }
}