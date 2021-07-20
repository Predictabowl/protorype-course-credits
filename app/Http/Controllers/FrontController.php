<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\Interfaces\UserFrontManager;
use App\Services\Interfaces\FrontManager;
use App\Factories\Interfaces\ManagersFactory;
use App\Services\Implementations\FrontManagerImpl;

class FrontController extends Controller
{
    public function index()
    {   
        return view("front.index",[
            "exams" => $this->getFrontManager()->getTakenExams()
        ]);
    }
    
    public function getOptions() {
        return view("front.showoptions",[
            "options" => $this->getFrontManager()->getExamOptions()
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
    
    
    private function getFrontManager(): FrontManager {
        //$userManager = app()->make(UserFrontManager::class);
        //return $userManager->getFrontManager();
        $factory = app()->make(ManagersFactory::class);
        return $factory->getFrontManager(auth()->user()->front->id);
    }
}
