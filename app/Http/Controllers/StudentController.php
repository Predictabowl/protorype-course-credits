<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
//use App\Repositories\Interfaces\CourseRepository;
use App\Services\Interfaces\UserFrontManager;

class StudentController extends Controller
{
    public function __construct() {
        $this->middleware(["auth","verified"]);
    }
    
    public function showFront()
    {   
        $frontManager = app()->make(UserFrontManager::class)->getFrontManager();
        return redirect()->route("frontView", [$frontManager->getFront()]);

    }
    
//    private function getStudyPlanBuilder(): StudyPlanBuilder{
//        $factory = app()->make(StudyPlanBuilderFactory::class);
//        //$builder = app()->make(StudyPlanBuilder::class);
//        $front = auth()->user()->front;
//        $builder = $factory->getStudyPlanBuilder($front->id, $front->course->id);
//        return $builder;
//    }
}
