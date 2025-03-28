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
            __DIR__.'/../config/babasultan23-dynamic-datatable.php', 'babasultan23-dynamic-datatable'
        );
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Views', 'dynamic-datatable');

        // Config publish
        $this->publishes([
            __DIR__.'/../config/babasultan23-dynamic-datatable.php' => config_path('babasultan23-dynamic-datatable.php'),
        ], 'config');

        // Views publish
        $this->publishes([
            __DIR__.'/Views' => resource_path('views/babasultan23/dynamic-datatable'),
        ], 'views');

        // JS publish
        $this->publishes([
            __DIR__.'/resources/js/babasultan23-dynamic-datatable.js' => public_path('babasultan23/js/babasultan23-crudAjax.js'),
        ], 'crudAjax');
    }
}
