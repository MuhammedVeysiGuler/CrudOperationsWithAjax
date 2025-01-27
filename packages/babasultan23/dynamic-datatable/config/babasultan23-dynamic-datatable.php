<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DataTable Varsayılan Seçenekleri
    |--------------------------------------------------------------------------
    |
    | Bunlar, bu paket kullanılarak oluşturulan tüm datatables için kullanılacak
    | varsayılan seçeneklerdir. Bu seçenekleri tablo bazında geçersiz
    | kılabilirsiniz.
    |
    */
    'options' => [
        'lengthMenu' => [[10, 25, 50, 100], [10, 25, 50, 100]],
        'responsive' => true,
        'scrollX' => true,
        'autoWidth' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Varsayılan CSS Sınıfları
    |--------------------------------------------------------------------------
    |
    | Bunlar, datatable öğeleri için kullanılacak varsayılan CSS sınıflarıdır.
    | Bu sınıfları tablo bazında geçersiz kılabilirsiniz.
    |
    */
    'classes' => [
        'table' => 'display nowrap dataTable cell-border',
        'container' => 'child-table-container p-3',
    ],

    /*
    |--------------------------------------------------------------------------
    | Varsayılan Görünüm Yolu
    |--------------------------------------------------------------------------
    |
    | Bu, datatable için kullanılacak varsayılan görünüm yoludur.
    | Bu yolu tablo bazında geçersiz kılabilirsiniz.
    |
    */
    'view' => 'dynamic-datatable::dynamic_datatable',
];
