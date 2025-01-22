<?php

namespace App\Services;

use App\Interfaces\BaseRepositoryInterface;
use App\Interfaces\BaseServiceInterface;
use Yajra\DataTables\DataTables;

abstract class BaseService implements BaseServiceInterface
{
    protected $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function findById($id)
    {
        return $this->repository->getById($id);
    }

    public function create(array $data)
    {
        return $this->repository->createNew($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->updateById($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->deleteById($id);
    }

    public function paginate($perPage = 25)
    {
        return $this->repository->getPaginated($perPage);
    }

    public function getDataTablesList($request)
    {
        $query = $this->repository->getAll();

        // Handle ordering
        $order = $request->input('order.0');
        if ($order) {
            $columnIndex = $order['column'];
            $columnName = $request->input("columns.{$columnIndex}.data");
            $columnDirection = $order['dir'];

            if ($columnName) {
                $query->orderBy($columnName, $columnDirection);
            }
        }

        return DataTables::of($query)
            ->addColumn('actions', function($row) {
                return $this->getActionButtons($row);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    protected function getActionButtons($row)
    {
        return view('panel.components.actions', [
            'row' => $row,
            'editRoute' => $this->getEditRoute($row),
            'deleteRoute' => $this->getDeleteRoute($row),
            'modelName' => $this->getModelName()
        ])->render();
    }

    protected function getEditRoute($row)
    {
        // Override in child classes
    }

    protected function getDeleteRoute($row)
    {
        // Override in child classes
    }

    protected function getModelName()
    {
        // Override in child classes
    }

    // Implement the getDataTable method
    public function getDataTable()
    {
        return $this->repository->getDataTable();
    }
}
