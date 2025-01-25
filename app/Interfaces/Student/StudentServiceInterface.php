<?php

namespace App\Interfaces\Student;

use App\Interfaces\BaseServiceInterface;

interface StudentServiceInterface extends BaseServiceInterface
{

    public function findByEmail(string $email);

    public function findByCity(string $city_name);

}
