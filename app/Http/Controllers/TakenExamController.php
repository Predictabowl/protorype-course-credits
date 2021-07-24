<?php

namespace App\Http\Controllers;

use App\Models\Front;
use App\Domain\TakenExamDTO;
use App\Services\Interfaces\UserFrontManager;
use App\Services\Interfaces\FrontManager;
use App\Factories\Interfaces\FrontManagerFactory;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TakenExamController extends Controller
{
    
    public function __construct() {
        $this->middleware(["auth","verified"]);
    }

    
    public function create(Front $front) {
        $attributes = request()->validate([
            "name" => ["required", "max:255"],
            "cfu" => ["required", "numeric", "min:1", "max:18"],
            "ssd" => ["required", Rule::exists("ssds", "code")]
        ]);

        $this->getFrontManager($front)->saveTakenExam($attributes);
        
        return back();
    }
    
    public function delete(Front $front){
        $attributes = request()->validate([
            "id" => ["numeric", Rule::exists("taken_exams","id")]
        ]);
        
        $this->getFrontManager($front)->deleteTakenExam($attributes["id"]);
        
        return back();
    }
    
    
    private function getFrontManager(Front $front): FrontManager {
        //$userManager = app()->make(UserFrontManager::class);
        //return $userManager->getFrontInfoManager();
        $factory = app()->make(FrontManagerFactory::class);
        return $factory->getFrontManager($front->id);
    }
    
}
