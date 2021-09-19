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
use App\Repositories\Interfaces\UserRepository;
use App\Repositories\Implementations\UserRepositoryImpl;
use App\Services\Interfaces\ExamDistance;
use App\Services\Implementations\ExamDistanceByName;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Services\Interfaces\UserFrontManager;
use App\Services\Implementations\UserFrontManagerImpl;
use App\Services\Interfaces\UserManager;
use App\Services\Implementations\UserManagerImpl;
use App\Services\Interfaces\FrontsSearchManager;
use App\Services\Implementations\FrontsSearchManagerImpl;
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
use App\Services\Interfaces\YearCalculator;
use App\Services\Implementations\YearCalculatorImpl;
use App\Factories\Interfaces\StudyPlanManagerFactory;
use App\Factories\Implementations\StudyPlanManagerFactoryImpl;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(StudyPlanManagerFactory::class, StudyPlanManagerFactoryImpl::class);
        
        //---- Mappers
        $this->app->bind(ExamBlockMapper::class, ExamBlockMapperImpl::class);
        $this->app->bind(ExamOptionMapper::class, ExamOptionMapperImpl::class);
        $this->app->bind(TakenExamMapper::class, TakenExamMapperImpl::class);
        
        //---- Repositories
        $this->app->bind(CourseRepository::class, CourseRepositoryImpl::class);
        $this->app->bind(ExamBlockRepository::class, ExamBlockRepositoryImpl::class);
        $this->app->bind(FrontRepository::class, FrontRepositoryImpl::class);
        $this->app->bind(TakenExamRepository::class, TakenExamRespositoryImpl::class);
        $this->app->bind(UserRepository::class, UserRepositoryImpl::class);
    
        //---- Services
        $this->app->bind(ExamDistance::class, ExamDistanceByName::class);
        $this->app->bind(StudyPlanBuilder::class, StudyPlanBuilderImpl::class);
        $this->app->bind(UserFrontManager::class, UserFrontManagerImpl::class);
        $this->app->bind(UserManager::class, UserManagerImpl::class);
        $this->app->bind(FrontsSearchManager::class, FrontsSearchManagerImpl::class);
        $this->app->bind(YearCalculator::class, YearCalculatorImpl::class);
        
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
