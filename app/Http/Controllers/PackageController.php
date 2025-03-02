<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Http\Request;
use BabaSultan23\DynamicDatatable\Facades\BabaSultan23DynamicDatatable;
use Yajra\DataTables\DataTables;

class PackageController extends Controller
{

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
            tableId: 'student-table',
            dataTableName: 'studentDatatable',
            columns: $columns,
            fetchUrl: route('package.fetch'),
            title: 'TITLE DENEME',
            options: [
                 'pageLength' => 10,  //config içinden de ayarlanbilir, tabloya özel custom özellik için
            ],
            filters: [
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
            plusButton: true,
            plusParentIdKey: 'parent_id',
            manuelSearch: false,
            language: 'tr' // default olarak da tr geliyor, yazmana gerek yok App::getLocale() ile dinamik kullanabilirsin
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
                'full_name' => "CONCAT(students.name, ' ', students.surname)",
                'lesson_name' => 'lessons.name',
                'email' => 'students.email',
                'city' => 'students.city'
            ])
            ->setActionButtons(function ($row) {
                return '<button onclick="updateStudent(' . $row->id . ')" class="btn btn-warning">Güncelle</button>
                        <button onclick="deleteStudent(' . $row->id . ')" class="btn btn-danger">Sil</button>';
            })
            ->setManuelSearchCallback(function ($query, $searchValue) {
                return $query->where(function($q) use ($searchValue) {
                    $q->where('lessons.name', 'like', "%{$searchValue}%");
                });
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
                    ->addColumn('full_name', function ($data) {
                        return $data->name . " " . $data->surname;
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
                    ->addColumn('actions', function ($row) {
                        return BabaSultan23DynamicDatatable::getActionButtons($row);
                    })
                    ->rawColumns(['plus', 'full_name', 'lesson_name', 'actions'])
                    ->make(true);
            });

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

        return BabaSultan23DynamicDatatable::processDataTableRequest($query, $request);
    }
}
