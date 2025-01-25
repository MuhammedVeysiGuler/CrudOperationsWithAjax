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
        $query = $this->model->query();

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
            ->rawColumns(['full_name', 'lesson_name', 'actions'])
            ->make(true);
    }


    protected function getActionButtonsDataTable($row)
    {
        return '<button onclick="updateStudent(' . $row->id . ')" class="btn btn-warning">Güncelle</button>
                <button onclick="deleteStudent(' . $row->id . ')" class="btn btn-danger">Sil</button>';
    }

}
