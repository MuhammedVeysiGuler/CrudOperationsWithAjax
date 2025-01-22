<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function createNew(array $data);
    public function updateById($id, array $data);
    public function deleteById($id);
    public function getPaginated($perPage = 25);
    public function getDataTable();
} 