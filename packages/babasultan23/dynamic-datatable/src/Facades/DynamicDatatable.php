<?php

namespace BabaSultan23\DynamicDatatable\Facades;

use Illuminate\Support\Facades\Facade;

class DynamicDatatable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dynamic-datatable';
    }
} 