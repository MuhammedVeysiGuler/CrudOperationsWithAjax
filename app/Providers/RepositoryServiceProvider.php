<?php

namespace App\Providers;

use App\Interfaces\NewsRepositoryInterface;
use App\Interfaces\NewsServiceInterface;
use App\Interfaces\SignInRepositoryInterface;
use App\Interfaces\SignInServiceInterface;
use App\Repositories\NewsRepository;
use App\Repositories\SignInRepository;
use App\Services\NewsService;
use App\Services\SignInService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SignInRepositoryInterface::class, SignInRepository::class);
        $this->app->bind(SignInServiceInterface::class, SignInService::class);
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(NewsServiceInterface::class, NewsService::class);
    }
} 