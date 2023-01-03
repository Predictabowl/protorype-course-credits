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
    
    private CourseRepository $courseRepo;
    private FrontRepository $frontRepo;
    
    public function __construct(CourseRepository $courseRepo, FrontRepository $frontRepo) {
        $this->courseRepo = $courseRepo;
        $this->frontRepo = $frontRepo;
    }

    
    public function getCourses(): Collection {
        return $this->courseRepo->getAll();
    }

    public function getCurrentCourse(Request $request): ?Course {
        if ($request->has("course")){
            return $this->courseRepo->get($request->get("course"));
        };
        
        return null;
    }

    public function getFilteredFronts(Request $request, int $pageSize = 50): Paginator {
        $filters = $request->only(["search","course"]);

        return $this->frontRepo->getAll($filters,$pageSize);
    }

}
