<?php

namespace App\Providers;

use App\Services\Interfaces\ExamDistance;
use App\Services\Implementations\ExamDistanceByName;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Factories\Implementations\RepositoriesFactoryImpl;
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
        $this->app->bind(RepositoriesFactory::class, RepositoriesFactoryImpl::class);
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
