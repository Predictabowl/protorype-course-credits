<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\UserFrontManager;
use App\Models\Front;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class StudyPlanController extends Controller
{
    
    public function __construct() {
        $this->middleware(["auth","verified"]);
    }

    public function show(Front $front){
        Gate::authorize("view-studyPlan", $front);
        
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
    
    public function createPdf(Front $front){
        Gate::authorize("view-studyPlan", $front);
        
        $plan = app()->make(UserFrontManager::class)
                ->setUserId($front->user_id)
                ->getStudyPlanBuilder()->getStudyPlan();
        
//        return view("studyplan.showplanPdf",[
//            "studyPlan" => $plan,
//            "front" => $front
//        ]);
        
        $pdf = \App::make("dompdf.wrapper");
        $pdf->setPaper("a4","landscape")
            ->loadView("studyplan.showplanPdf",[
                "studyPlan" => $plan,
                "front" => $front
            ]);
        return $pdf->stream();
        
    }
    
//    private function buildPlan(Front $front, string $viewName): \Illuminate\View\View{
//        Gate::authorize("view-studyPlan", $front);
//        
//        $builder = app()->make(UserFrontManager::class)
//                ->setUserId($front->user_id)
//                ->getStudyPlanBuilder();
//        if (!isset($builder)){
//            return back()->with("studyPlanFailure", "Non è stato selezionato alcun corso di laurea."); //should send a notification
//        }
//        return view($viewName,[
//            "studyPlan" => $builder->getStudyPlan(),
//            "front" => $front
//        ]);
//    }
}
