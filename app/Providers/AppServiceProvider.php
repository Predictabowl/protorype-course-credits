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
use App\Services\Interfaces\UserFrontManager;
use App\Services\Implementations\UserFrontManagerImpl;
use App\Factories\Interfaces\FrontInfoManagerFactory;
use App\Factories\Implementations\FrontInfoManagerFactoryImpl;
use App\Mappers\Interfaces\TakenExamMapper;
use App\Mappers\Implementations\TakenExamMapperImpl;

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
        $this->app->bind(UserFrontManager::class, UserFrontManagerImpl::class);
        $this->app->bind(FrontInfoManagerFactory::class, FrontInfoManagerFactoryImpl::class);
        $this->app->bind(TakenExamMapper::class, TakenExamMapperImpl::class);
        $this->app->bind(TakenExamMapper::class, TakenExamMapperImpl::class);
        
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
