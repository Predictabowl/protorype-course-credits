<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\Interfaces\UserFrontManager;
use App\Repositories\Interfaces\CourseRepository;
use App\Factories\Interfaces\ManagersFactory;
use App\Models\Front;
use Illuminate\Support\Facades\Gate;

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
        return "Hello World!";
    }
    
    public function show(Front $front)
    {   
        $this->authorize("view",$front);
        $manager = app()->make(ManagersFactory::class)->getFrontManager($front->id);
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
        $manager = app()->make(ManagersFactory::class)->getFrontManager($front->id);
        $manager->setCourse(request()->get("courseId"));
        return back();
    }
    

       
//    public function create() {
//        $attributes = request()->validate([
//            "name" => ["required", "max:255"],
//            "cfu" => ["required", "numeric", "min:1", "max:18"],
//            "ssd" => ["required", Rule::exists("ssds", "code")]
//        ]);
//        
//        $exam = new TakenExamDTO(0, $attributes["name"], $attributes["ssd"], $attributes["cfu"]);
//        $this->getFrontManager()->saveTakenExam($exam);
//        
//        return back();
//    }
    
//    public function delete(int $id){
//        $this->getFrontManager()->deleteTakenExam($id);
//        return back();
//    }
    
}
