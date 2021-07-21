<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\Interfaces\UserFrontManager;
use App\Factories\Interfaces\ManagersFactory;
use App\Models\Front;
use Illuminate\Support\Facades\Gate;

class FrontController extends Controller
{
    
    public function __construct() {
        
        $this->middleware(["auth","verified"]);
        // This policy automatically makes viewAny fails... this is a Laravel bug
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
        return view("front.show",[
            "exams" => $manager->getTakenExams()
        ]);
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
