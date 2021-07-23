<?php

namespace App\Providers;

use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Implementations\FrontRepositoryImpl;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Repositories\Implementations\TakenExamRespositoryImpl;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Implementations\ExamBlockRepositoryImpl;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Implementations\CourseRepositoryImpl;
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
use App\Mappers\Interfaces\ExamBlockMapper;
use App\Mappers\Implementations\ExamBlockMapperImpl;
use App\Mappers\Interfaces\ExamOptionMapper;
use App\Mappers\Implementations\ExamOptionMapperImpl;
use App\Factories\Interfaces\ManagersFactory;
use App\Factories\Implementations\ManagersFactoryImpl;
use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Factories\Implementations\StudyPlanBuilderFactoryImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //---- Factories
        $this->app->bind(FrontInfoManagerFactory::class, FrontInfoManagerFactoryImpl::class);
        //$this->app->bind(RepositoriesFactory::class, RepositoriesFactoryImpl::class); //to remove
        $this->app->bind(StudyPlanBuilderFactory::class, StudyPlanBuilderFactoryImpl::class);
        
        //---- Mappers
        $this->app->bind(ExamBlockMapper::class, ExamBlockMapperImpl::class);
        $this->app->bind(ExamOptionMapper::class, ExamOptionMapperImpl::class);
        $this->app->bind(TakenExamMapper::class, TakenExamMapperImpl::class);
        
        //---- Repositories
        $this->app->bind(ExamBlockRepository::class, ExamBlockRepositoryImpl::class);
        $this->app->bind(FrontRepository::class, FrontRepositoryImpl::class);
        $this->app->bind(TakenExamRepository::class, TakenExamRespositoryImpl::class);
        $this->app->bind(CourseRepository::class, CourseRepositoryImpl::class);
    
        //---- Services
        $this->app->bind(ExamDistance::class, ExamDistanceByName::class);
        $this->app->bind(StudyPlanBuilder::class, StudyPlanBuilderImpl::class);
        $this->app->bind(UserFrontManager::class, UserFrontManagerImpl::class);
        $this->app->bind(ManagersFactory::class, ManagersFactoryImpl::class);
        
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
