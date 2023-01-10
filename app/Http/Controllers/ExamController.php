<?php

namespace App\Http\Controllers;

use App\Domain\NewExamInfo;
use App\Exceptions\Custom\ExamNotFoundException;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Services\Interfaces\CourseAdminManager;
use Illuminate\Validation\Rule;
use function back;
use function request;

class ExamController extends Controller
{
    private CourseAdminManager $courseManager;
    
    public function __construct(CourseAdminManager $courseManager) {
        $this->middleware(["auth","verified"]);
        $this->courseManager = $courseManager;
    }

    public function post(ExamBlock $examblock){
        $this->authorize("create", Course::class);
        $attr = $this->attributeValidation();
        $examInfo = new NewExamInfo($attr["name"],
                $attr["ssd"], $attr["freeChoice"]);
        
        $this->courseManager->saveExam($examInfo, $examblock->id);
        
        return back();
    }
    
    public function delete(Exam $exam){
        $this->authorize("delete", Course::class);
        
        $this->courseManager->deleteExam($exam->id);
        
        return back();
    }
    
    public function put(Exam $exam){
        $this->authorize("create", Course::class);
        $attr = $this->attributeValidation();
        $examInfo = new NewExamInfo($attr["name"], $attr["ssd"],
                $attr["freeChoice"]);
        
        try{
            $this->courseManager->updateExam($examInfo, $exam->id);
        } catch(ExamNotFoundException $ex) {
            return back()->with("error",__("Missing Entity"));
        }
        return back();
    }
    
    private function attributeValidation(): array{
        $validationRules = [
            "name" => ["required", "string"],
            "ssd" => [Rule::exists("ssds","code")],
            "freeChoice" => ["required","boolean"],
         ];
        $inputs = request()->all();
        $freeChoice = $inputs["freeChoice"];
        if ($freeChoice == true) {
            $validationRules["ssd"] = ["string"];
            $inputs["ssd"] = "";
            request()->replace($inputs);
        }
        
        return request()->validate($validationRules);
     }    
}
