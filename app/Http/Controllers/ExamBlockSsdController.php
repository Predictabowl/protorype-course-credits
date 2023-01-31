<?php

namespace App\Http\Controllers;

use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Services\Interfaces\ExamBlockSsdManager;
use function request;

class ExamBlockSsdController extends Controller
{
    private ExamBlockSsdManager $ebSsdManager;
    
    public function __construct(ExamBlockSsdManager $ebSsdManager) {
        $this->middleware(["auth","verified"]);
        $this->ebSsdManager = $ebSsdManager;
    }
    
    public function show(int $examBlockId){
        $this->authorize("viewany", Course::class);
        
        try {
            $examBlock = $this->ebSsdManager->eagerLoadExamBlock($examBlockId);
        } catch (ExamBlockNotFoundException $exc) {
            return back()->with("error", $exc->getMessage());
        }
        
        return view("courses.examBlock.examBlockSsds",[
            "examBlock" => $examBlock
        ]);
    }
        
    public function post(int $examBlockId){
        $this->authorize("create", Course::class);
        
        $ssd = request("ssd");
        try {
            $this->ebSsdManager->addSsd($examBlockId, $ssd);
        } catch (ExamBlockNotFoundException $ex){
            return redirect(route("courseIndex"))
                    ->with("error", $ex->getMessage());
        }
        
        return back();
    }
    
    public function delete(ExamBlock $examBlock, int $ssdId){
        $this->authorize("delete", Course::class);
    }
    
//    public function post(Course $course){
//        $this->authorize("create", Course::class);
//
//        $attributes = $this->attributeValidation();
//        $ebInfo = new NewExamBlockInfo(
//                $attributes["maxExams"],
//                $attributes["cfu"],
//                $attributes["courseYear"]);
//        
//        $this->courseManager->saveExamBlock($ebInfo, $course->id);
//        
//        return back()->with("success",__("Added Exam Block"));
//    }
//    
//    public function delete(ExamBlock $examblock){
//        $this->authorize("delete", Course::class);
//        
//        $this->courseManager->deleteExamBlock($examblock->id);
//        
//        return back();
//    }
//    
//    public function put(ExamBlock $examblock){
//        $this->authorize("create", Course::class);
//        $attributes = $this->attributeValidation();
//        $ebInfo = new NewExamBlockInfo(
//                $attributes["maxExams"],
//                $attributes["cfu"],
//                $attributes["courseYear"]);
//        try{
//            $this->courseManager->updateExamBlock($ebInfo, $examblock->id);
//        } catch (ExamBlockNotFoundException $ex) {
//            return back()->with("error",__("Missing Entity"));
//        }
//        
//        return back();
//    }
    
    private function attributeValidation(): array{
         return request()->validate([
            "cfu" => ["required", "numeric"],
            "courseYear" => ["numeric"],
            "maxExams" => ["required","numeric"],
         ]);
     }
    
}
