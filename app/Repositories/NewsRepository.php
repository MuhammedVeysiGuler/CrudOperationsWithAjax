<?php

namespace App\Repositories;

use App\Interfaces\NewsRepositoryInterface;
use App\Models\News;
use App\Traits\DataTableTrait;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    use DataTableTrait;

    public function __construct(News $model)
    {
        parent::__construct($model);
    }

    public function getPublishedList()
    {
        return $this->model->where('is_published', true)->get();
    }

    protected function formatName($data)
    {
        return $data->title;
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

    public function getDataTable()
    {
        return $this->model->query(); // Return the query builder instance
    }
}
