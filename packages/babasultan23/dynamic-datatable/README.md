# Dynamic DataTable Package for Laravel

Bu paket, Laravel projelerinde DataTables kullanımını kolaylaştırmak için geliştirilmiş bir pakettir. Özellikle karmaşık sorgular, özelleştirilmiş sıralama ve arama işlemleri için kullanışlı bir API sunar.

## Gereksinimler

- PHP ^7.3|^8.0
- Laravel ^8.0|^9.0|^10.0
- Yajra DataTables ^9.0

## Kurulum

1. Composer ile paketi yükleyin:
```bash
composer require muhammedveysiguler/dynamic-datatable
```

2. Service Provider ve Facade otomatik olarak yüklenir. Manuel eklemek isterseniz `config/app.php` dosyasına ekleyin:

```php
'providers' => [
    // ...
    Muhammedveysiguler\DynamicDatatable\DynamicDatatableServiceProvider::class,
],

'aliases' => [
    // ...
    'DynamicDatatable' => Muhammedveysiguler\DynamicDatatable\Facades\DynamicDatatable::class,
]
```

3. Konfigürasyon ve view dosyalarını publish edin:
```bash
php artisan vendor:publish --provider="Muhammedveysiguler\DynamicDatatable\DynamicDatatableServiceProvider"
```

## Kullanım

### Temel Kullanım

#### Controller:
```php
use Muhammedveysiguler\DynamicDatatable\Facades\DynamicDatatable;

class YourController extends Controller
{
    public function index()
    {
        $columns = [
            ['data' => 'id', 'title' => 'ID'],
            ['data' => 'name', 'title' => 'Ad'],
            ['data' => 'email', 'title' => 'E-posta'],
            ['data' => 'actions', 'title' => 'İşlemler', 'orderable' => false]
        ];

        $dataTable = DynamicDatatable::render(
            tableId: 'my-table',
            dataTableName: 'myDatatable',
            columns: $columns,
            fetchUrl: route('data.fetch'),
            title: 'Tablo Başlığı'
        );

        return view('your-view', compact('dataTable'));
    }
}
```

#### Blade View:
```blade
<!DOCTYPE html>
<html>
<head>
    <title>DataTable Örneği</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- DataTable'ı göstermek için sadece {!! $dataTable !!} kullanın -->
        {!! $dataTable !!}
    </div>

    <!-- Gerekli JavaScript dosyaları -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

### Detaylı Fonksiyon Kullanımları

#### 1. setOrderMapping()
Özel sıralama tanımlamaları için kullanılır. Özellikle birleştirilmiş alanlar veya ilişkili tablolardaki alanlar için kullanışlıdır.

```php
DynamicDatatable::setOrderMapping([
    'full_name' => "CONCAT(students.name, ' ', students.surname)",
    'lesson_name' => 'lessons.name',
]);
```

#### 2. setSearchMapping()
Arama yapılacak alanları ve nasıl aranacaklarını tanımlar.

```php
DynamicDatatable::setSearchMapping([
    'full_name' => "CONCAT(students.name, ' ', students.surname)",
    'lesson_name' => 'lessons.name',
    'email' => 'students.email',
    'city' => 'students.city'
]);
```

#### 3. setActionButtons()
Her satır için aksiyon butonlarını tanımlar.

```php
DynamicDatatable::setActionButtons(function($row) {
    return '
        <button onclick="edit('.$row->id.')" class="btn btn-warning">Düzenle</button>
        <button onclick="delete('.$row->id.')" class="btn btn-danger">Sil</button>
    ';
});
```

#### 4. setFormatResponse()
DataTable yanıtını özelleştirmek için kullanılır.

```php
DynamicDatatable::setFormatResponse(function($query, $totalRecords, $filteredRecords) {
    return DataTables::of($query)
        ->with([
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
        ])
        ->addColumn('full_name', function($row) {
            return $row->first_name . ' ' . $row->last_name;
        })
        ->addColumn('lesson_name', function ($data) {
            return $data->lesson_name;
        })
        ->filterColumn('full_name', function ($query, $keyword) {
            $query->whereRaw("CONCAT(students.name, ' ', students.surname) LIKE ?", ["%{$keyword}%"]);
        })
        ->filterColumn('lesson_name', function ($query, $keyword) {
            $query->where('lessons.name', 'like', "%{$keyword}%");
        })
        ->addColumn('actions', function($row) {
            return DynamicDatatable::getActionButtons($row);
        })
        ->rawColumns(['actions'])
        ->make(true);
});
```

#### 5. render()
DataTable'ı oluşturur ve görüntüler.

```php
DynamicDatatable::render(
    tableId: 'users-table',              // DataTable id
    dataTableName: 'usersTable',         // DataTable adı
    columns: $columns,                   // Tablo kolonları
    fetchUrl: route('users.fetch'),      // Veri çekme URL'i
    title: 'Kullanıcılar',               // Tablo başlığı
    options: [                           // DataTable seçenekleri || boş array [] gönderilebilir
        'pageLength' => 10,      // default 10 
        'processing' => true,
        'serverSide' => true,
        'scrollX' => true,
        'stateSave' => false,
    ],
    filters: [           
                // Herhangi bir filtre olmaması durumunda [] şeklinde gönderebilirsiniz
                // 'filters' => []  |||||  'filters' => ['html' => [], 'js'=>[]] şeklinde kullanılır
                'html' => '
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="cityFilter" class="filter-label">Şehir Seç:</label>
                            <select id="cityFilter" class="form-control city-select">
                                <option value="">Tüm Şehirler</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="dateFilter" class="filter-label">(Örnek) - Tarih:</label>
                            <input type="date" id="dateFilter" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="statusFilter" class="filter-label">(Örnek) - Durum:</label>
                            <select id="statusFilter" class="form-control">
                                <option value="">Tümü</option>
                                <option value="active">Aktif</option>
                                <option value="passive">Pasif</option>
                            </select>
                        </div>
                    </div>
                ',
                'js' => [
                    'filterElements' => ['cityFilter', 'dateFilter', 'statusFilter'],
                    'filterData' => "
                        d.city = $('#cityFilter').val();
                        d.date = $('#dateFilter').val();
                        d.status = $('#statusFilter').val();
                    "
                ]
            ],
    plusButton: true,                    // Alt menü butonu gösterimi | default : false. true olarak gönderildiğinde dinamik child tableda renderlanır
    plusParentIdKey: 'parent_id'         // Alt menü ilişki anahtarı => gönderilen değişkene request->değişken_adı ($request->parent_id) şeklinde ulaşılabilir.
);
```

### Fetch Metodu Örneği

```php
public function fetch(Request $request)
{
    $query = Student::query();

    $parentId = $request->parent_id ?? [];
    if ($parentId != 'null' && $parentId) {
        $query = $query->where('parent_id', $parentId);
    } else {
        $query = $query->whereNull('parent_id');
    }

    $query->leftJoin('lessons', 'students.lesson_id', '=', 'lessons.id')
        ->select('students.*', 'lessons.name as lesson_name');

    if (isset($request->city) && $request->city !== '') {
        $query->where('students.city', $request->city);
    }

    $result = DynamicDatatable::handleDataTableQuery($query, $request);

    return DynamicDatatable::formatDataTableResponse(
        $result['query'],
        $result['totalRecords'],
        $result['filteredRecords']
    );
}
```

## Method Chaining

Tüm ayar metodları zincirleme kullanılabilir:

```php
DynamicDatatable::setOrderMapping([...])
    ->setSearchMapping([...])
    ->setActionButtons(function($row) { ... })
    ->setFormatResponse(function($query, $totalRecords, $filteredRecords) { ... });
```

## Özelleştirme

### 1. View Özelleştirme
Publish edilen view dosyasını düzenleyerek tabloyu özelleştirebilirsiniz:
```bash
resources/views/vendor/dynamic-datatable/dynamic_datatable.blade.php
```

### 2. Konfigürasyon
Publish edilen config dosyasından varsayılan ayarları değiştirebilirsiniz:
```bash
config/dynamic-datatable.php
```

### Özel Filtreli Kullanım Örneği:
```blade
<!DOCTYPE html>
<html>
<head>
    <title>Filtreli DataTable Örneği</title>
    <!-- Gerekli CSS dosyaları -->
</head>
<body>
    <div class="container">
        <!-- DataTable -->
        {!! $dataTable !!}
    </div>

    <!-- Gerekli JavaScript dosyaları -->
    {{--    FİLTRELEME JS BAŞLANGIÇ   --}}
    <script type="text/javascript">
        var cities = @json($cities);

        $(document).ready(function () {
            if (cities && cities.length > 0) {
                let citySelect = $('#cityFilter');

                // Dinamik şehir seçeneklerini oluştur
                cities.forEach(function (city) {
                    citySelect.append('<option value="' + city + '">' + city + '</option>');
                });
            }
        });
    </script>
    {{--    FİLTRELEME JS BİTİŞ    --}}
</body>
</html>
```

## Lisans

MIT License. Detaylar için [LICENSE](LICENSE) dosyasına bakın. 
