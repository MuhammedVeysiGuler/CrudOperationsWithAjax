<?php

namespace App\Services;

use App\Interfaces\NewsRepositoryInterface;
use App\Interfaces\NewsServiceInterface;
use App\Repositories\NewsRepository;
use App\Traits\DataTableTrait;
use Yajra\DataTables\DataTables;

class NewsService extends BaseService implements NewsServiceInterface
{
    use DataTableTrait;

    protected $repository;

    public function __construct(NewsRepository $repository)
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
        $query = $this->repository->getDataTable(); // Get the query builder instance
        return DataTables::of($query) // Use DataTables to format the response
            ->addColumn('actions', function ($row) {
                return $this->getActionButtons($row); // Ensure you have this method to return action buttons
            })
            ->rawColumns(['actions'])
            ->make(true); // Return the JSON response
    }

    public function getPublished()
    {
        return $this->repository->getPublished();
    }

    protected function getEditRoute($row)
    {
        return route('news.update_view', $row->id);
    }

    protected function getDeleteRoute($row)
    {
        return route('news.delete');
    }

    protected function getModelName()
    {
        return 'News';
    }
}
