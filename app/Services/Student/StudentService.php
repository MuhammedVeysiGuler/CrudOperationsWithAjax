<?php

namespace App\Services\Student;

use App\Helpers\Helper;
use App\Interfaces\Student\StudentRepositoryInterface;
use App\Interfaces\Student\StudentServiceInterface;
use App\Services\BaseService;

class StudentService extends BaseService implements StudentServiceInterface
{
    protected $studentRepository;

    public function __construct(StudentRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->studentRepository = $repository;
    }

    public function findByEmail(string $email)
    {
        return $this->studentRepository->findByEmail($email);
    }

    public function findByCity(string $city_name)
    {
        return $this->studentRepository->findByCity($city_name);
    }

}
