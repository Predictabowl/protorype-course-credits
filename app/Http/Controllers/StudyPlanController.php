<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\UserFrontManager;
use App\Models\Front;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class StudyPlanController extends Controller
{
    
    public function __construct() {
        $this->middleware(["auth","verified"]);
    }

    public function show(Front $front){
        if(Gate::denies("view-studyPlan", $front)){
            abort(403);
        }
        
        $builder = app()->make(UserFrontManager::class)
                ->setUserId($front->user_id)
                ->getStudyPlanBuilder();
        if (!isset($builder)){
            return back()->with("studyPlanFailure", "Non è stato selezionato alcun corso di laurea."); //should send a notification
        }
        return view("studyplan.showplan",[
            "studyPlan" => $builder->getStudyPlan(),
            "front" => $front
        ]);
    }
}
