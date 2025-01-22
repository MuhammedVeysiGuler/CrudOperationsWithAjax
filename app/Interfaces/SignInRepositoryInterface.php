<?php

namespace App\Interfaces;

interface SignInRepositoryInterface extends BaseRepositoryInterface
{
    public function getDataTablesList($request);
} 