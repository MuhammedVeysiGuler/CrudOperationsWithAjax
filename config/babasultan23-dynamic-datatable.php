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
        // Sayfa başına gösterilecek kayıt sayıları (lengthMenu, ikinci array seçim metni)
        'lengthMenu' => [
            [10, 25, 50, 100, -1], // Kayıt sayısı seçenekleri
            [10, 25, 50, 100, 'Tümü'] // Kullanıcıya gösterilecek metin
        ],

        // Veritabanından veri çekme işlemi aktif hale getirildi (Ajax üzerinden veri getirilmesi için)
        'processing' => true, // Veri işleme sırasında kullanıcıya gösterilen "loading" simgesi

        // Sunucu tarafı işlemler için true yapılır, veri sunucudan çekilir ve sayfalama yapılır
        'serverSide' => true, // Sunucu taraflı veri işleme

        // Responsive özellik aktif, ekran boyutuna göre tablo düzeni adapte olur
        'responsive' => true, // Mobil uyumlu görünüm

        // Tablo durumu sayfa yenilendiğinde kaydedilmez
        'stateSave' => false, // Sayfa yenilenince önceki filtre ve sıralama durumu kaydedilmez

        // Yatay kaydırma özelliği aktif
        'scrollX' => true, // Tabloyu sağa sola kaydırmak için

        // Otomatik genişlik ayarları devre dışı bırakılır
        'autoWidth' => true, // Kolon genişlikleri manuel olarak ayarlanır, otomatik genişlik devre dışı

        // Veri tablosu yüklenirken arama yapılabilir
        'searching' => true, // Tablo üzerinde arama yapılabilir

        // Tabloyu sıralama yapılabilir hale getirir
        'ordering' => true, // Tabloyu sıralamak için tıklanabilir başlıklar

        // Tablonun üzerine fare ile gelindiğinde satır vurgulama (highlight) efekti
        'hover' => true, // Satır üzerine gelindiğinde vurgulama efekti

        // Satırlarda ve sütunlarda sıralama yapılabilir
        'order' => [[0, 'asc']], // İlk sütun (index 0) varsayılan olarak artan sırayla sıralanacak

        // Sayfalama işlemi yapılabilir
        'paging' => true, // Sayfalama özelliği aktif
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
];
