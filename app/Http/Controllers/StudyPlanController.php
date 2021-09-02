<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\UserFrontManager;
use App\Models\Front;
use App\Domain\StudyPlan;
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
        
        return $this->setupDomPdf($front, $plan)->stream("prospetto.pdf");
    }
    
    private function setupDomPdf(Front $front, StudyPlan $studyPlan){
        $pdf = \App::make("dompdf.wrapper");
        $pdf->setPaper("a4","portrait")
            ->loadView("studyplan.showplanPdf",[
                "studyPlan" => $studyPlan,
                "front" => $front
            ]);
        
        $footer = "{PAGE_NUM} / {PAGE_COUNT}";
        $size = 10;
        $domPDF = $pdf->getDomPDF();
        $font = $domPDF->getFontMetrics()->getFont("Serif");
        $width = $domPDF->getFontMetrics()->get_text_width($footer, $font, $size) / 2;
        $x = ($domPDF->getCanvas()->get_width() - $width) -20;
        $y = $domPDF->getCanvas()->get_height() - 35;
        $domPDF->getCanvas()->page_text($x, $y, $footer, $font, $size);
        
        return $pdf;
    }

}
