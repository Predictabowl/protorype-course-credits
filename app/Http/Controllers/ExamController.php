<?php

namespace App\Http\Controllers;

use App\Domain\NewExamInfo;
use App\Exceptions\Custom\ExamNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Services\Interfaces\CourseAdminManager;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
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
        $validator = $this->attributeValidation();
        if($validator->fails()){
            return Response::view("components.courses.flash-error", [
                "errors" => $validator->errors()->all()],422);
        }
        $attr = $validator->getData();
        
        try {
            $examInfo = new NewExamInfo($attr["name"],
                    $attr["ssd"], $attr["freeChoice"]);
        } catch (InvalidArgumentException $exc) {
            return Response::view("components.courses.flash-error", [
                "errors" => [$exc->getMessage()]],422);
        }

        try {
            $exam = $this->courseManager->saveExam($examInfo, $examblock->id);
        } catch (SsdNotFoundException $exc) {
            return Response::view("components.courses.flash-error", [
                "errors" => [$exc->getMessage()]],422);
        }
//        return Response::noContent();
        return Response::view("components.courses.exam-row",["exam" => $exam]);
    }
    
    public function delete(Exam $exam){
        $this->authorize("delete", Course::class);
        
        $this->courseManager->deleteExam($exam->id);
        
        return Response::noContent();
    }
    
    public function put(Exam $exam){
        $this->authorize("create", Course::class);
        $validator = $this->attributeValidation();
        if($validator->fails()){
            return Response::view("components.courses.flash-error", [
                "errors" => $validator->errors()->all()],422);
        }
        $attr = $validator->getData();
        
        try {
            $examInfo = new NewExamInfo($attr["name"],
                    $attr["ssd"], $attr["freeChoice"]);
        } catch (InvalidArgumentException $exc) {
            return Response::view("components.courses.flash-error", [
                "errors" => [$exc->getMessage()]],422);
        }

        try{
            $this->courseManager->updateExam($examInfo, $exam->id);
        } catch(ExamNotFoundException $exc) {
            return Response::view("components.courses.flash-error", [
                "errors" => [$exc->getMessage()]],404);
        } catch(SsdNotFoundException $exc) {
            return Response::view("components.courses.flash-error", [
                "errors" => [$exc->getMessage()]],422);
        }
        return Response::noContent();
    }
    
    private function attributeValidation(): Validator{
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
        request()->replace($inputs);
        $validator = ValidatorFacade::make(request()->all(), $validationRules);
        return $validator;
     }    
}
