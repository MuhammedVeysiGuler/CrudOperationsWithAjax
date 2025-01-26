<?php

namespace App\Repositories\Student;

use App\Interfaces\Student\StudentRepositoryInterface;

use App\Models\Student;
use App\Repositories\BaseRepository;
use Yajra\DataTables\DataTables;

class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{

    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByCity(string $city_name)
    {
        return $this->model->where('city', $city_name)->get();
    }


    public function getDataTable($filters = [])
    {

        $parentId = $filters['parent_id'] ?? []; // alt menü | parent-child | değişken bladeden dinamik olarak gonderiliyor
        if ($parentId != 'null' && $parentId) {
            $query = $this->model->query()->where('parent_id', $parentId); // Alt menüleri filtrele
        } else {
            $query = $this->model->query()->whereNull('parent_id'); // Üst menüleri getir (parent_id null olanlar)
        }

        // "lesson_name" için join işlemi
        $query->leftJoin('lessons', 'students.lesson_id', '=', 'lessons.id')
            ->select('students.*', 'lessons.name as lesson_name');

        // Filtereleri uygula
        if (isset($filters['city']) && $filters['city'] !== '') {
            $query->where('students.city', $filters['city']);
        }

        $result = $this->handleDataTableQuery($query, request());

        return $this->formatDataTableResponse(
            $result['query'],
            $result['totalRecords'],
            $result['filteredRecords']
        );
    }

    protected function getOrderMappingDataTable()
    {
        return [
            'full_name' => "CONCAT(students.name, ' ', students.surname)", // students tablosundaki full_name
            'lesson_name' => 'lessons.name', // lessons tablosundaki name
        ];
    }

    protected function getSearchMappingDataTable()
    {
        return [
            'full_name' => "CONCAT(students.name, ' ', students.surname)", // students tablosundaki full_name
            'lesson_name' => 'lessons.name',
            'email' => 'students.email',
            'city' => 'students.city'
        ];
    }


    protected function formatDataTableResponse($query, $totalRecords, $filteredRecords)
    {
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
                return $this->getActionButtonsDataTable($row);
            })
            ->rawColumns(['plus', 'full_name', 'lesson_name', 'actions'])
            ->make(true);
    }


    protected function getActionButtonsDataTable($row)
    {
        return '<button onclick="updateStudent(' . $row->id . ')" class="btn btn-warning">Güncelle</button>
                <button onclick="deleteStudent(' . $row->id . ')" class="btn btn-danger">Sil</button>';
    }

}
