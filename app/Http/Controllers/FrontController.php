<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\FrontRepository;
use App\Services\Interfaces\FrontManager;
use App\Repositories\Interfaces\CourseRepository;
use App\Factories\Interfaces\FrontManagerFactory;
use App\Models\Front;

class FrontController extends Controller
{
    
    public function __construct() {
        
        $this->middleware(["auth","verified"]);
        // This auto biding policy makes viewAny fails... this is a Laravel bug
        // It's better to use the $this->authorize() method
        //$this->middleware("can:view,front");
        //$this->middleware("can:viewAny,App/Front");
    }
    
    public function index(){
        $this->authorize("viewAny", Front::class);
        
// this should be done in the service layer
        $courses = app()->make(CourseRepository::class)->getAll();
        if (request()->has("course")){
            $currentCourse = $courses->first(fn($course) => 
                    $course->id == request()->get("course"));
        } else {
            $currentCourse = null;
        }
        
        return view("front.index", [
            "fronts" => app()->make(FrontRepository::class)
                ->getAll(request(["search","course"])),
            "courses" => $courses,
            "currentCourse" => $currentCourse
        ]);
    }
    
    public function show(Front $front)
    {   
        $this->authorize("view",$front);
        $manager = $this->makeFrontManager($front->id);
        $courseRepo = app()->make(CourseRepository::class);
        return view("front.show",[
            "exams" => $manager->getTakenExams(),
            "front" => $manager->getFront(),
            "courses" => $courseRepo->getAll()
        ]);
    }
    
    public function put(Front $front)
    {   
        $this->authorize("update",$front);
        //no validation to be done because is not user input
        $manager = $this->makeFrontManager($front->id);
        $manager->setCourse(request()->get("courseId"));
        return back();
    }
    
    private function makeFrontManager($id): FrontManager{
        return app()->make(FrontManagerFactory::class)->getFrontManager($id);
    }
    
}
