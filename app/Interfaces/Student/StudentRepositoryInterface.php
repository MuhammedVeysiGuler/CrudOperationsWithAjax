<?php

namespace App\Interfaces\Student;

use App\Interfaces\BaseRepositoryInterface;

interface StudentRepositoryInterface extends BaseRepositoryInterface
{

    public function findByEmail(string $email);

    public function findByCity(string $city_name);

}
