<?php

namespace App\Services\Student;

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

    // Özel metodlar ekleyebiliriz
    public function findByEmail($email)
    {
        return $this->studentRepository->findByEmail($email);
    }

    public function findByCity($city)
    {
        return $this->studentRepository->findByCity($city);
    }

    // Override edilmiş metodlar ||  Ekstra iş mantığı
    public function create(array $data)
    {
        if (isset($data['email'])) {
            $data['email'] = strtolower($data['email']);
        }

        return parent::create($data);
    }

    // Override edilmiş metodlar ||  Ekstra iş mantığı
    public function update($id, array $data)
    {

        if (isset($data['email'])) {
            $data['email'] = strtolower($data['email']);
        }

        return parent::update($id, $data);
    }

    public function getDataTable($filters = [])
    {
        return $this->studentRepository->getDataTable($filters);
    }
}
