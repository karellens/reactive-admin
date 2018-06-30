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
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'reactiveadmin');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'reactiveadmin');

        $this->publishes([__DIR__.'/../config/reactiveadmin.php' => config_path('reactiveadmin.php')], 'config');
        $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/reactiveadmin')], 'views');
        $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/reactiveadmin')], 'public');

        $this->loadRoutesFrom(__DIR__.'/routes.php');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Karellens\ReactiveAdmin\Repositories\ReactiveAdmin', function ($app) {
            return new \Karellens\ReactiveAdmin\Repositories\ReactiveAdmin;
        });
//        $this->mergeConfigFrom(
//            __DIR__.'/../config/reactiveadmin.php', 'reactiveadmin'
//        );
    }
}