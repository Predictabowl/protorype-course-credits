<?php

namespace App\Http\Controllers;

use App\Domain\TakenExamDTO;
use App\Services\Interfaces\FrontManager;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TakenExamController extends Controller
{
    
    public function create() {
        $attributes = request()->validate([
            "name" => ["required", "max:255"],
            "cfu" => ["required", "numeric", "min:1", "max:18"],
            "ssd" => ["required", Rule::exists("ssds", "code")]
        ]);
        
        $exam = new TakenExamDTO(0, $attributes["name"], $attributes["ssd"], $attributes["cfu"]);
        $this->getFrontManager()->saveTakenExam($exam);
        
        return back();
    }
    
    public function delete(){
        $attributes = request()->validate([
            "id" => ["numeric", Rule::exists("taken_exams","id")]
        ]);
        
        $this->getFrontManager()->deleteTakenExam($attributes["id"]);
        
        return back();
    }
    
    
    private function getFrontManager(): FrontManager {
        $front = app()->make(FrontManager::class);
        $front->setFront(auth()->user()->front->id);
        return $front;
    }
}
