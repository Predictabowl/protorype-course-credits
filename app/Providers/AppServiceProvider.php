<?php

namespace App\Providers;

//use App\Repositories\Implementations\CourseExamRepositoryImpl;
//use App\Repositories\Interfaces\CourseExamRepository;
use App\Services\Interfaces\ExamDistance;
use App\Services\Implementations\ExamDistanceByName;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Services\Interfaces\DTOMapper;
use App\Services\Implementations\DTOMapperImpl;
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
        $this->app->bind(ExamDistance::class, ExamDistanceByName::class);
        $this->app->bind(StudyPlanBuilder::class, StudyPlanBuilderImpl::class);
        $this->app->bind(DTOMapper::class, DTOMapperImpl::class);
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
