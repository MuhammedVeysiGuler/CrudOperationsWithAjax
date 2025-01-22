<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function createNew(array $data)
    {
        return $this->model->create($data);
    }

    public function updateById($id, array $data)
    {
        $record = $this->getById($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return false;
    }

    public function deleteById($id)
    {
        return $this->model->destroy($id);
    }

    public function getPaginated($perPage = 25)
    {
        return $this->model->paginate($perPage);
    }

    public function getDataTable()
    {
        throw new \Exception("Method getDataTable not implemented.");
    }
}
