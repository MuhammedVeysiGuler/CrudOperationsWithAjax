<?php

namespace App\Services;

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
        return $this->repository->createNewData($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->updateById($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->deleteById($id);
    }

    public function paginate($perPage = 10)
    {
        return $this->repository->getPaginated($perPage);
    }

    public function getDataTable()
    {
        return $this->repository->getDataTable();
    }

}
