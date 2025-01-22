<?php

namespace App\Services;

use App\Interfaces\SignInRepositoryInterface;
use App\Interfaces\SignInServiceInterface;
use App\Repositories\SignInRepository;
use App\Traits\DataTableTrait;
use Yajra\DataTables\DataTables;

class SignInService extends BaseService implements SignInServiceInterface
{
    use DataTableTrait;

    protected $repository;

    public function __construct(SignInRepository $repository)
    {
        parent::__construct($repository);
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDataTable()
    {
        $query = $this->repository->getDataTable();
        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return $this->getActionButtons($row);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    protected function getEditRoute($row)
    {
        return route('signin.update_view', $row->id);
    }

    protected function getDeleteRoute($row)
    {
        return route('sign_in.delete');
    }

    protected function getModelName()
    {
        return 'SignIn';
    }
}
