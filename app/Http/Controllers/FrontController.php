<?php

namespace App\Http\Controllers;

use App\Domain\TakenExamDTO;
use Illuminate\Http\Request;
use App\Services\Interfaces\FrontManager;

class FrontController extends Controller
{
    public function index()
    {
        $front = app()->make(FrontManager::class);
        $front->setFront(auth()->user()->front->id);
        
        return view("front.index",[
            "exams" =>  $front->getTakenExams()
        ]);
    }
    
    public function create() {
        $values = request()->validate([
            "name" => "required",
            "cfu" => ["required", "numeric"],
            "ssd" => "required"
        ]);
        
        //ddd($values);
        $exam = new TakenExamDTO(0, $values["name"], $values["ssd"], $values["cfu"]);
        $front = app()->make(FrontManager::class);
        $front->setFront(auth()->user()->front->id)->saveTakenExam($exam);
        
        return back();
    }
}
