<?php

namespace App\Traits;

use Yajra\DataTables\DataTables;

trait DataTableTrait
{
    public function getDataTablesList($request)
    {
        $query = $this->model->query();

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
            ->editColumn('name', function ($data) {
                return $this->formatName($data);
            })
            ->addColumn('actions', function($row) {
                return $this->getActionButtons($row);
            })
            ->rawColumns(['name', 'actions'])
            ->make(true);
    }

    public function getDataTable()
    {
        $query = $this->repository->getDataTable(); // Call the repository's getDataTable
        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return view('panel.components.actions', ['modelName' => class_basename($row), 'row' => $row]);
            })
            ->make(true);
    }

    // Bu metot her repository'de override edilebilir
    protected function formatName($data)
    {
        return $data->name;
    }

    // Bu metot her repository'de override edilebilir
    protected function getActionButtons($row)
    {
        return view('panel.components.actions', [
            'row' => $row,
            'editRoute' => $this->getEditRoute($row),
            'deleteRoute' => $this->getDeleteRoute($row),
            'modelName' => $this->getModelName()
        ])->render();
    }

    abstract protected function getEditRoute($row);
    abstract protected function getDeleteRoute($row);
    abstract protected function getModelName();
} 