<?php

namespace App\Providers;

use App\Repositories\Implementations\CourseExamRepositoryImpl;
use App\Repositories\Interfaces\CourseExamRepository;
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
        $this->app->bind(CourseExamRepository::class, CourseExamRepositoryImpl::class);
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
