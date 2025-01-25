<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{

    public function getAllData();

    public function getById($id);

    public function createNewData(array $data);

    public function updateById($id, array $data);

    public function deleteById($id);

    public function getDataTable();

}
