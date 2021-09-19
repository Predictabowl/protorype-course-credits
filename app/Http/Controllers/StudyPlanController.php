<?php

namespace App\Http\Controllers;

use App\Factories\Interfaces\StudyPlanManagerFactory;
use App\Services\Interfaces\StudyPlanManager;
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
        
        $manager = app()->make(StudyPlanManagerFactory::class)
                ->get($front);
                
        $studyPlan = $manager->getStudyPlan();
        if (!isset($studyPlan)){
            return back()->with("studyPlanFailure", __("A degree course must be selected"));
        }
        request()->session()->put("studyPlan",$studyPlan);
        return view("studyplan.showplan",[
            "studyPlan" => $studyPlan,
            "front" => $front,
        ]);
    }
    
    public function createPdf(Front $front){
        Gate::authorize("view-studyPlan", $front);
        
        $plan = session()->get("studyPlan");
        return $this->setupDomPdf($front, $plan)
                ->stream($front->user->name." - Valutazione Carriera.pdf");
    }
    
    private function setupDomPdf(Front $front, StudyPlan $studyPlan){
        $pdf = \App::make("dompdf.wrapper");
        $pdf->setPaper("a4","portrait")
            ->loadView("studyplan.showplanPdf",[
                "studyPlan" => $studyPlan,
                "front" => $front
            ]);
        
        $footer = $front->user->name." - {PAGE_NUM} / {PAGE_COUNT}";
        $size = 10;
        $domPDF = $pdf->getDomPDF();
        $font = $domPDF->getFontMetrics()->getFont("Serif");
        $width = $domPDF->getFontMetrics()->get_text_width($footer, $font, $size) / 2;
        $x = ($domPDF->getCanvas()->get_width() - $width) -30;
        $y = $domPDF->getCanvas()->get_height() - 35;
        $domPDF->getCanvas()->page_text($x, $y, $footer, $font, $size);
        
        return $pdf;
    }

}
