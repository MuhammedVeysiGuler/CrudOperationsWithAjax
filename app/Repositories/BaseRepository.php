<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Traits\DataTableTrait;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    use DataTableTrait;

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAllData()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function createNewData(array $data)
    {
        return $this->model->create($data);
    }

    public function updateById($id, array $data)
    {
        $record = $this->getById($id);
        $record->update($data);
        return $record;
    }

    public function deleteById($id)
    {
        return $this->model->destroy($id);
    }

    public function getPaginated($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function getDataTable($filters = [])
    {
        $query = $this->model->query();
        $result = $this->handleDataTableQuery($query, request());
        return $this->formatDataTableResponse(
            $result['query']
            ->orderBy('created_at','desc'),
            $result['totalRecords'],
            $result['filteredRecords']
        );
    }
}
