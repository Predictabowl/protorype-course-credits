<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\StudyPlanBuilder;
use Illuminate\Http\Request;
use App\Factories\Interfaces\StudyPlanBuilderFactory;

class StudyPlanController extends Controller
{
    
    public function __construct() {
        $this->middleware(["auth","verified"]);
    }

    public function index(){
        return view("studyplan.showplan",[
            "studyPlan" => $this->getStudyPlanBuilder()->getStudyPlan()
        ]);
    }
    
    private function getStudyPlanBuilder(): StudyPlanBuilder{
        $factory = app()->make(StudyPlanBuilderFactory::class);
        //$builder = app()->make(StudyPlanBuilder::class);
        $front = auth()->user()->front;
        $builder = $factory->getStudyPlanBuilder($front->id, $front->course->id);
        return $builder;
    }
}
