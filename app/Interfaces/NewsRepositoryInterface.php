<?php

namespace App\Interfaces;

interface NewsRepositoryInterface extends BaseRepositoryInterface
{
    public function getPublishedList();
} 