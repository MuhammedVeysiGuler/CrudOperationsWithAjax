<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Interfaces\BaseRepositoryInterface;
use App\Interfaces\BaseServiceInterface;

abstract class BaseService implements BaseServiceInterface
{

    protected $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAllData();
    }

    public function findById($id)
    {
        return $this->repository->getById($id);
    }


    public function create(array $data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = Helper::scriptStripper($value);
        }
        return $this->repository->createNewData($data);
    }


    public function update($id, array $data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = Helper::scriptStripper($value);
        }
        return $this->repository->updateById($id, $data);
    }


    public function delete($id)
    {
        return $this->repository->deleteById($id);
    }


    public function getDataTable($filters = [])
    {
        return $this->repository->getDataTable($filters);
    }

}
