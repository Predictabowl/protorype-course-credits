<?php

namespace App\Http\Controllers;

use App\Factories\Interfaces\FrontManagerFactory;
use App\Models\Front;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\FrontsSearchManager;
use function app;
use function back;
use function request;
use function view;

class FrontController extends Controller
{
    
    private FrontManagerFactory $frontManagerFactory;
    
    public function __construct(FrontManagerFactory $frontManagerFactory) {
        
        $this->middleware(["auth","verified"]);
        // The following auto biding policy makes viewAny fails... this is a Laravel bug
        // It's better to use the $this->authorize() method
        //$this->middleware("can:view,front");
        //$this->middleware("can:viewAny,App/Front");
        $this->frontManagerFactory = $frontManagerFactory;
    }
    
    public function index(){
        $this->authorize("viewAny", Front::class);
        
        $manager = app()->make(FrontsSearchManager::class);
        
        return view("front.index", [
            "fronts" => $manager->getFilteredFronts(request(),25),
            "courses" => $manager->getCourses(),
            "currentCourse" => $manager->getCurrentCourse(request())
        ]);
    }
    
    public function show(Front $front)
    {   
        $this->authorize("view",$front);
        
        $manager = $this->makeFrontManager($front->id);
        return view("front.show",[
            "exams" => $manager->getTakenExams(),
            "front" => $front,
            "courses" => $manager->getCourses()->sortBy("name")
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
        return $this->frontManagerFactory->getFrontManager($id);
    }
    
}
