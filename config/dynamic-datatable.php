<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DataTable Default Options
    |--------------------------------------------------------------------------
    |
    | These are the default options that will be used for all datatables
    | rendered using this package. You can override these options on a
    | per-table basis.
    |
    */
    'options' => [
        'lengthMenu' => [[10, 25, 50, 100], [10, 25, 50, 100]],
        'responsive' => true,
        'autoWidth' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Classes
    |--------------------------------------------------------------------------
    |
    | These are the default CSS classes that will be used for the datatable
    | elements. You can override these classes on a per-table basis.
    |
    */
    'classes' => [
        'table' => 'display nowrap dataTable cell-border',
        'container' => 'child-table-container p-3',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default View Path
    |--------------------------------------------------------------------------
    |
    | This is the default view path that will be used for the datatable.
    | You can override this path on a per-table basis.
    |
    */
    'view' => 'dynamic-datatable::dynamic_datatable',
];
