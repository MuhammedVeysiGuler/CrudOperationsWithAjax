<?php

namespace App\Providers;

use App\Interfaces\Student\StudentRepositoryInterface;
use App\Interfaces\Student\StudentServiceInterface;
use App\Repositories\Student\StudentRepository;
use App\Services\Student\StudentService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(StudentServiceInterface::class, StudentService::class);
    }
} 