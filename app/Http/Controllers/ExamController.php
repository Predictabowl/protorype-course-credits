<?php

namespace App\Http\Controllers;

use App\Domain\NewExamInfo;
use App\Exceptions\Custom\ExamNotFoundException;
use App\Http\Controllers\Support\ControllerHelpers;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Services\Interfaces\CourseAdminManager;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\ValidationException;
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
                $attr["ssd"],
                $attr["freeChoice"]);
        $exam = $this->courseManager->saveExam($examInfo, $examblock->id);

        return Response::view("components.courses.exam-row",["exam" => $exam]);
    }
    
    public function delete(Exam $exam){
        $this->authorize("delete", Course::class);
        
        $this->courseManager->deleteExam($exam->id);
        
        return Response::noContent();
    }
    
    public function put(Exam $exam){
        $this->authorize("create", Course::class);
        $attr = $this->attributeValidation();
        
        $examInfo = new NewExamInfo($attr["name"],
                $attr["ssd"],
                $attr["freeChoice"]);
        
        $editedExam = $this->courseManager->updateExam($examInfo, $exam->id);
        return Response::view("components.courses.exam-row",[
            "exam" => $editedExam]);
    }
    
    private function attributeValidation(): array{
        $validationRules = [
            "name" => ["required", "string"],
            "ssd" => ["nullable"],
            "freeChoice" => ["nullable"],
         ];
        $inputs = request()->all();
        $freeChoice = isset($inputs["freeChoice"]) ? true : false;
        if ($freeChoice) {
            $inputs["ssd"] = null;
        }
        $inputs["freeChoice"] = $freeChoice;
        $validator = ValidatorFacade::make($inputs, $validationRules);
        if($validator->fails()){
            throw new ValidationException($validator, 
                ControllerHelpers::flashResponse(
                    $validator->errors()->all(), 422));
        }
        return $validator->getData();
    }
}
