<?php

namespace App\Providers;

use App\Services\Interfaces\ExamDistance;
use App\Services\Implementations\ExamDistanceByName;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Factories\Implementations\RepositoriesFactoryImpl;
use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\FrontInfoManager;
use App\Services\Implementations\FrontInfoManagerImpl;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Implementations\FrontRepositoryImpl;
use App\Services\Interfaces\UserFrontManager;
use App\Services\Implementations\UserFrontManagerImpl;
use App\Repositories\Interfaces\UserRepository;
use App\Repositories\Implementations\UserRepositoryImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ExamDistance::class, ExamDistanceByName::class);
        $this->app->bind(StudyPlanBuilder::class, StudyPlanBuilderImpl::class);
        $this->app->bind(RepositoriesFactory::class, RepositoriesFactoryImpl::class);
        $this->app->bind(FrontInfoManager::class, FrontInfoManagerImpl::class);
        $this->app->bind(FrontRepository::class, FrontRepositoryImpl::class);
        $this->app->bind(UserFrontManager::class, UserFrontManagerImpl::class);
        $this->app->bind(UserRepository::class, UserRepositoryImpl::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
