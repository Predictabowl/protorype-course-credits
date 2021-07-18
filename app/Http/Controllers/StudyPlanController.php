<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\StudyPlanBuilder;
use Illuminate\Http\Request;

class StudyPlanController extends Controller
{
    public function index(){
        return view("studyplan.showplan",[
            "studyPlan" => $this->getStudyPlanBuilder()->getStudyPlan()
        ]);
    }
    
    private function getStudyPlanBuilder(): StudyPlanBuilder{
        $builder = app()->make(StudyPlanBuilder::class);
        $builder->setFront(auth()->user()->front->id);
        return $builder;
    }
}
