# Dynamic DataTable Package for Laravel

Bu paket, Laravel projelerinde DataTables kullanımını kolaylaştırmak için geliştirilmiş bir pakettir. Özellikle karmaşık sorgular, özelleştirilmiş sıralama ve arama işlemleri için kullanışlı bir API sunar.

## Gereksinimler

- PHP : ^7.3|^8.0
- Laravel : ^8.0|^9.0|^10.0
- Yajra DataTables : *

## Kurulum

1. Composer ile paketi yükleyin:
```bash
composer require babasultan23/dynamic-datatable
```

2. Service Provider ve Facade otomatik olarak yüklenir. Manuel eklemek isterseniz `config/app.php` dosyasına ekleyin:

```php
'providers' => [
    // ...
    BabaSultan23\DynamicDatatable\DynamicDatatableServiceProvider::class,
],

'aliases' => [
    // ...
    'BabaSultan23DynamicDatatable' => BabaSultan23\DynamicDatatable\Facades\BabaSultan23DynamicDatatable::class,
]
```

3. Konfigürasyon dosyasını publish edin:
```bash
php artisan vendor:publish --provider="BabaSultan23\DynamicDatatable\DynamicDatatableServiceProvider" --tag=config
php artisan vendor:publish --provider="BabaSultan23\DynamicDatatable\DynamicDatatableServiceProvider" --tag=crudAjax
```

## Kullanım

### Temel Kullanım

#### Controller:
```php
use BabaSultan23\DynamicDatatable\Facades\BabaSultan23DynamicDatatable;

class YourController extends Controller
{
    public function index()
    {
        $columns = [
            ['data' => 'id', 'title' => 'ID'],
            ['data' => 'name', 'title' => 'Ad'],
            ['data' => 'email', 'title' => 'E-posta'],
            ['data' => 'actions', 'title' => 'İşlemler', 'orderable' => 'false']
        ];

        $dataTable = BabaSultan23DynamicDatatable::render(
            tableId: 'my-table',                // DataTable id    <table id="{{ my-table }}" >
            dataTableName: 'myDatatable',       // DataTable adı    var {{ myDatatable }} = $('#{{ $tableId }}').DataTable({
            columns: $columns,                  // Tablo kolonları
            fetchUrl: route('data.fetch'),      // Veri çekme URL'i
            title: 'Tablo Başlığı'              // Tablo Başlığı    <h2 class="pageheader-title">{{ $title }}</h2>
        );

        return view('your-view', compact('dataTable'));
    }
}
```

#### Blade View:
```html
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
Özel sıralama tanımlamaları için kullanılır. Özellikle birleştirilmiş alanlar veya ilişkili tablolardaki alanlar için kullanışlıdır. <br>
Özellikle orjinal model tablosunun içinde bulunmayan, sonradan addColumn ile eklenen değerler için kullanılır.
```php
BabaSultan23DynamicDatatable::setOrderMapping([ //tablodaki kolon adı => veri tabanı karşılığı
    'full_name' => "CONCAT(students.name, ' ', students.surname)",
    'lesson_name' => 'lessons.name',
]);
```

#### 2. setSearchMapping()
Arama yapılacak alanları ve nasıl aranacaklarını tanımlar. Bu kısıma dahil edilmeyen kolonlar aramaya dahil edilmez. <br>
**Aranmasını istenilen kısımlar eklenmek zorunda!!**
```php
BabaSultan23DynamicDatatable::setSearchMapping([ //tablodaki kolon adı => veri tabanı karşılığı
    'full_name' => "CONCAT(students.name, ' ', students.surname)",
    'lesson_name' => 'lessons.name',
    'email' => 'students.email',
    'city' => 'students.city'
]);
```

#### 3. setActionButtons()
Her satır için aksiyon butonlarını tanımlar. `Silme` `Güncelleme` `Onaylama` vb. ön taraf ile dinamik çalışan butonlar için kullanılır.

```php
BabaSultan23DynamicDatatable::setActionButtons(function($row) {
    return '
        <button onclick="edit('.$row->id.')" class="btn btn-warning">Düzenle</button>
        <button onclick="delete('.$row->id.')" class="btn btn-danger">Sil</button>
    ';
});
```

#### 4. setFormatResponse()
DataTable yanıtını özelleştirmek için kullanılır. <br>
`->with([`  ile başlayan kod bloğuna **karışmayınız**, kullanılmak zorunda.
```php
BabaSultan23DynamicDatatable::setFormatResponse(function($query, $totalRecords, $filteredRecords) {
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
            return BabaSultan23DynamicDatatable::getActionButtons($row);
        })
        ->rawColumns(['actions'])
        ->make(true);
});
```

#### 5. render()
DataTable'ı oluşturur ve görüntüler. İlgili blade dosyasına değişken olarak gönderilir. <br>
```php
 $dataTable = BabaSultan23DynamicDatatable::render(
     ....
     ....
 return view('',compact('dataTable'));
```

```php
BabaSultan23DynamicDatatable::render(
    tableId: 'users-table',              // DataTable id    <table id="my-table" >
    dataTableName: 'usersTable',         // DataTable adı    var myDatatable = $('#{{ $tableId }}').DataTable({
    columns: $columns,                   // Tablo kolonları
    fetchUrl: route('users.fetch'),      // Veri çekme URL'i
    title: 'Kullanıcılar',               // Tablo Başlığı    <h2 class="pageheader-title">{{ $title }}</h2>
    options: [                           // DataTable seçenekleri || boş array [] gönderilebilir. //default ayarlar config dosyası içerisinde yer almakta.
        'pageLength' => 10,      // default 10 
        'scrollX' => true,
        'stateSave' => false,
    ],
    filters: [           
                // Herhangi bir filtre olmaması durumunda [] şeklinde gönderebilirsiniz
                // 'filters' => []  veya  'filters' => ['html' => [], 'js'=>[]] şeklinde kullanılabilir
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
#### Child-Parent Tablosunu Ayarlama
Eğer ilişkili bir parent-child yapısı var ise aşağıdaki adımları takip etmelisiniz:

1. DataTable oluştururken:
   - `plusButton: true` ve `plusParentIdKey: 'request_param'` değeri fetch fonksiyonunda request ile yakalayacağınız değer olarak tanımlanmalıdır (örn: `$request->request_param`)

2. Columns dizisine plus butonu için sütun eklemelisiniz:
   ```php
   ['data' => 'plus', 'title' => '', 'orderable' => 'false']
   ```

3. `setFormatResponse` kısmına plus butonu eklenmelidir:
   ```php
   ->addColumn('plus', function ($data) {
       if (Model::where('parent_id', $data->id)->count() > 0) {
           return '<button class="btn btn-success sub-menu-button"><i class="fa fa-plus-circle"></i></button>';
       }
   })
   ```
   **_Not: Bu buton ve column kısmını direkt olarak kullanmanız önerilir._**<br>


4. Request'ten gelen değişkene göre: <br> (`plusParentIdKey: 'request_param'` yani `$request->request_param`) sorguya göre child sorgunuzu fetch içerisinde tanımlayıp tabloya geri döndürmelisiniz:
   ```php
   $query = Model::query();
   
   $parentId = $request->request_param ?? [];
   if ($parentId != null && $parentId) {
       $query = $query->where('parent_id', $parentId);
   } else {
       $query = $query->whereNull('parent_id');
   }
   ```

### Fetch Metodu Örneği
Model sorgusu sadece `query builder` olarak,  `Model::query()` şeklinde kullanılmalıdır
```php
public function fetch(Request $request)
{
    $query = Student::query();   // Veri yalnızca query builder olarak sağlanmalıdır. all(), get(), take(), limit() gibi yöntemlerin kullanılması desteklenmemektedir.

    $parentId = $request->parent_id ?? [];
    if ($parentId != 'null' && $parentId) {
        $query = $query->where('parent_id', $parentId);
    } else {
        $query = $query->whereNull('parent_id');
    }

    $query->leftJoin('lessons', 'students.lesson_id', '=', 'lessons.id')   // Varsa relationlarınız bu şekilde eklenebilir
        ->select('students.*', 'lessons.name as lesson_name');

    if (isset($request->city) && $request->city !== '') {
        $query->where('students.city', $request->city);
    }

    $result = BabaSultan23DynamicDatatable::handleDataTableQuery($query, $request);   //ZORUNLU

    return BabaSultan23DynamicDatatable::formatDataTableResponse(                     //ZORUNLU
        $result['query'],
        $result['totalRecords'],
        $result['filteredRecords']
    );
}
```

## Method Chaining

Tüm ayar metodları zincirleme kullanılabilir:

```php
BabaSultan23DynamicDatatable::setOrderMapping([...])
    ->setSearchMapping([...])
    ->setActionButtons(function($row) { ... })
    ->setFormatResponse(function($query, $totalRecords, $filteredRecords) { ... });
```


### Chaning Örnek
```php
public function index()
    {
        $columns = [
            ['data' => 'id', 'title' => 'ID'],
            ['data' => 'plus', 'title' => '', 'orderable' => 'false'],
            ['data' => 'full_name', 'title' => 'Ad - Soyad'],
            ['data' => 'lesson_name', 'title' => 'Ders Adı'],
            ['data' => 'email', 'title' => 'Mail'],
            ['data' => 'city', 'title' => 'İl'],
            ['data' => 'actions', 'title' => 'İşlemler', 'orderable' => 'false']
        ];

        $dataTable = BabaSultan23DynamicDatatable::render(
            tableId: 'tableId',
            dataTableName: 'dataTableName',
            columns: $columns,
            fetchUrl: route('fetchUrl'),
            title: ' ... ',
            options: [ ... ],
            filters: [ ... ],
            plusButton: true,
            plusParentIdKey: 'plusParentIdKey'
        );

        $cities = ['Ankara', 'Istanbul', 'Izmir'];
        $lessons = Lesson::all();

        return view('package-test', compact('dataTable', 'cities', 'lessons'));
    }

    public function fetch(Request $request)
    {
        BabaSultan23DynamicDatatable::setOrderMapping([
            'full_name' => "CONCAT(students.name, ' ', students.surname)",
            'lesson_name' => 'lessons.name',
        ])
            ->setSearchMapping([
                'email' => 'email',
                'city' => 'students.city'
            ])
            ->setActionButtons(function ($row) {
                return '<button onclick="updateStudent(' . $row->id . ')" class="btn btn-warning">Güncelle</button>
                        <button onclick="deleteStudent(' . $row->id . ')" class="btn btn-danger">Sil</button>';
            })
            ->setFormatResponse(function ($query, $totalRecords, $filteredRecords) {
                return DataTables::of($query)
                    ->with([
                        'recordsTotal' => $totalRecords,
                        'recordsFiltered' => $filteredRecords,
                    ])
                    ->addColumn('plus', function ($data) {
                        if (Student::where('parent_id', $data->id)->count() > 0) {
                            return '<button class="btn btn-success sub-menu-button"><i class="fa fa-plus-circle"></i></button>';
                        }
                    })
                    ->addColumn('addColumn', function ($data) {
                        return $data->addColumn;
                    })
                    ->filterColumn('addColumnFilter', function ($query, $keyword) {
                        $query->where('addColumn', 'like', "%{$keyword}%");
                    })
                    ->addColumn('actions', function ($row) {
                        return BabaSultan23DynamicDatatable::getActionButtons($row);
                    })
                    ->rawColumns(['addColumn', 'addColumnFilter', 'plus', 'actions'])
                    ->make(true);
            });

        $query = Model::query();

        $result = BabaSultan23DynamicDatatable::handleDataTableQuery($query, $request);

        return BabaSultan23DynamicDatatable::formatDataTableResponse(
            $result['query'],
            $result['totalRecords'],
            $result['filteredRecords']
        );
    }


```
## Özelleştirme

### 1. View Özelleştirme
View dosyasını publish ederek tabloyu özelleştirebilirsiniz:
```bash
php artisan vendor:publish --provider="BabaSultan23\DynamicDatatable\DynamicDatatableServiceProvider" --tag=views <

path:
    resources/babasultan23/dynamic-datatable/dynamic_datatable.blade.php
```

### 2. Konfigürasyon
Publish edilen config dosyasından varsayılan ayarları değiştirebilirsiniz:
```bash
path:
    config/babasultan23-dynamic-datatable.php
```

### 3. Ajax Js
Publish edilen ajax js dosyasından varsayılan ayarları değiştirebilirsiniz:
```bash
path:
    public/babasultan23/js/babasultan23-crudAjax.js
```

### Özel Filtreli Kullanım Örneği:
```html
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

## Dinamik Ajax Crud Kullanımı
```html
Layout blade dosyanıza js yolunu ve csrf tokenın çalışması için  <head> içine aşağıdakileri ekleyiniz:
    <head>
        .....
        .....
            <script src="{{ asset('babasultan23/js/babasultan23-crudAjax.js') }}"></script>
            <script>
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            </script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        .....
        .....
    </head>
```
### Örnek Kullanım
```javascript
Yukarıdaki eklemelerin tamamlandığı ve ilgili Blade dosyasına modal entegrasyonunun gerçekleştirildiği varsayılarak:
     
           function createStudent() {
            createAjax(
                "studentDatatable",              // DataTable Name
                "create_student",                // formId
                "{{ route('student.create') }}", // URL
                "#add-modal",                    // modalId
                "Kayıt başarıyla oluşturuldu!"   // successMessage (optional)
            );
        }

        function deleteStudent(id) {
            deleteAjax(
                "studentDatatable",              // DataTable Name
                "",                              // formId
                "{{ route('student.delete') }}", // URL
                "",                              // modalId
                "Kayıt başarıyla silindi!",      // successMessage (optional)
                id                               // Silinecek Datanın Id değeri
            );
        }

        function updateStudent(id) {
            var fieldMapping = { // Input ID - Returning Response Key
                'nameUpdate': 'name',
                'surnameUpdate': 'surname',
                'cityUpdate': 'city',
                'mailUpdate': 'email',
                'updateId': 'updateId',
                'lesson_idUpdate': 'lesson_id'
            };

            getAjaxData(
                "studentDatatable",              // DataTable Name (opsiyonel)
                "{{ route('student.get') }}",    // URL
                "#update_modal",                 // modalId
                fieldMapping,                    // fieldMapping parametresini gönderin
                id                               // Veri ID'si
            );
        }

        function updateStudentPost() {
            updateAjax(
                "studentDatatable",              // DataTable Name
                "update_student",                // formId
                "{{ route('student.update') }}", // URL
                "#update_modal",                 // modalId
                "Kayıt başarıyla güncellendi!"   // successMessage (optional)
            );
        }

```
## Lisans

MIT License. Detaylar için [LICENSE](LICENSE) dosyasına bakın. 
