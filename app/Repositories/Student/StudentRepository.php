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

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByCity($city)
    {
        return $this->model->where('city', $city)->get();
    }

    public function getDataTable($filters = [])
    {
        $query = $this->model->query();

        // Filtereleri uygula
        if (isset($filters['city']) && $filters['city'] !== '') {
            $query->where('city', $filters['city']);
        }

        $result = $this->handleDataTableQuery($query, request());

        return $this->formatDataTableResponse(
            $result['query'],
            $result['totalRecords'],
            $result['filteredRecords']
        );
    }

    protected function getOrderMapping()
    {
        return [
            'full_name' => "CONCAT(name, ' ', surname)",
            // Add more mappings as needed
        ];
    }

    protected function getSearchMapping()
    {
        return [
            'full_name' => "CONCAT(name, ' ', surname)",
            // Add more mappings as needed
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
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(name, ' ', surname) LIKE ?", ["%{$keyword}%"]);
            })
            ->addColumn('actions', function ($row) {
                return $this->getActionButtons($row);
            })
            ->rawColumns(['full_name', 'actions'])
            ->make(true);
    }

    protected function getActionButtons($row)
    {
        return '<button onclick="updateStudent(' . $row->id . ')" class="btn btn-warning">Güncelle</button>
                <button onclick="deleteStudent(' . $row->id . ')" class="btn btn-danger">Sil</button>';
    }

}
