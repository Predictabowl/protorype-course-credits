<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\UserFrontManager;
use App\Factories\Interfaces\ManagersFactory;
use App\Models\Front;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Factories\Interfaces\StudyPlanBuilderFactory;

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
        // missing the check on course, the builder will be null if the course is not set
        return view("studyplan.showplan",[
            "studyPlan" => $builder->getStudyPlan()
        ]);
    }
}
