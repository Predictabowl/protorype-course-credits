<?php

namespace App\Http\Controllers;

use App\Models\Front;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\FrontsSearchManager;
use function back;
use function request;
use function view;

class FrontController extends Controller
{
    
    private FrontManager $frontManager;
    private FrontsSearchManager $frontsSearchManager;
    
    public function __construct(FrontManager $frontManager,
            FrontsSearchManager $frontsSearchManager) {
        
        $this->middleware(["auth","verified"]);
        // The following auto biding policy makes viewAny fails... this is a Laravel bug
        // It's better to use the $this->authorize() method
        //$this->middleware("can:view,front");
        //$this->middleware("can:viewAny,App/Front");
        $this->frontManager = $frontManager;
        $this->frontsSearchManager = $frontsSearchManager;
    }
    
    public function index(){
        $this->authorize("viewAny", Front::class);
        
        return view("front.index", [
            "fronts" => $this->frontsSearchManager->getFilteredFronts(request(),25),
            "courses" => $this->frontsSearchManager->getCourses(),
            "currentCourse" => $this->frontsSearchManager->getCurrentCourse(request())
        ]);
    }
    
    public function show(Front $front)
    {   
        $this->authorize("view",$front);
        
        return view("front.show",[
            "exams" => $this->frontManager->getTakenExams($front->id),
            "front" => $front,
            "courses" => $this->frontsSearchManager->getCourses()
        ]);
    }
    
    public function put(Front $front)
    {   
        $this->authorize("update",$front);
        $this->frontManager->setCourse(
                $front->id, request()->get("courseId"));
        return back();
    }
    
}
