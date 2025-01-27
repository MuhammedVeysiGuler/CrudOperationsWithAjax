<?php

namespace BabaSultan23\DynamicDatatable;

use Illuminate\Support\ServiceProvider;

class DynamicDatatableServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('dynamic-datatable', function ($app) {
            return new DynamicDatatable();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/dynamic-datatable.php', 'dynamic-datatable'
        );
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Views', 'dynamic-datatable');

        $this->publishes([
            __DIR__.'/../config/dynamic-datatable.php' => config_path('dynamic-datatable.php'),
            __DIR__.'/Views' => resource_path('views/vendor/dynamic-datatable'),
        ]);
    }
} 