<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Implementations;

use App\Models\Course;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Interfaces\CourseRepository;
use App\Services\Interfaces\FrontsSearchManager;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;


/**
 * Description of FrontsManagerImpl
 *
 * @author piero
 */
class FrontsSearchManagerImpl implements FrontsSearchManager{
    
    private $courses;
    
    public function __construct() {
        $this->courses = app()->make(CourseRepository::class)->getAll();
    }

    
    public function getCourses(): Collection {
        return $this->courses;
    }

    public function getCurrentCourse(): ?Course {
         if (request()->has("course")){
            return $this->getCourses()->first(fn($course) => 
                    $course->id == request()->get("course"));
        };
        
        return $currentCourse = null;
    }

    public function getFilteredFronts(int $pageSize = 50): Paginator {
        return app()->make(FrontRepository::class)
                ->getAll(request(["search","course"]),$pageSize);
    }

}
