<?php

namespace App\Repositories;

use App\Interfaces\SignInRepositoryInterface;
use App\Models\SignIn;
use App\Traits\DataTableTrait;

class SignInRepository extends BaseRepository implements SignInRepositoryInterface
{
    use DataTableTrait;

    public function __construct(SignIn $model)
    {
        parent::__construct($model);
    }

    public function getDataTable()
    {
        return $this->model->query(); // Return the query builder instance
    }

    protected function formatName($data)
    {
        return $data->name . " " . $data->surname;
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

    public function getAll()
    {
        return $this->model->query(); // Ensure this returns the correct data
    }
}
