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
use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\UserFrontManager;
use App\Services\Implementations\UserFrontManagerImpl;
use App\Mappers\Interfaces\TakenExamMapper;
use App\Mappers\Implementations\TakenExamMapperImpl;
use App\Mappers\Interfaces\ExamBlockMapper;
use App\Mappers\Implementations\ExamBlockMapperImpl;
use App\Mappers\Interfaces\ExamOptionMapper;
use App\Mappers\Implementations\ExamOptionMapperImpl;
use App\Factories\Interfaces\FrontManagerFactory;
use App\Factories\Implementations\FrontManagerFactoryImpl;
use App\Factories\Interfaces\CourseManagerFactory;
use App\Factories\Implementations\CourseManagerFactoryImpl;
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
        $this->app->bind(CourseManagerFactory::class, CourseManagerFactoryImpl::class);
        $this->app->bind(FrontManagerFactory::class, FrontManagerFactoryImpl::class);
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
