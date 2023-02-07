<?php

namespace App\Http\Controllers;

use App\Domain\NewExamBlockInfo;
use App\Http\Controllers\Support\ControllerHelpers;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Services\Interfaces\CourseManager;
use App\Services\Interfaces\ExamBlockManager;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use function __;
use function back;
use function redirect;
use function request;
use function route;
use function view;

class ExamBlockController extends Controller
{
    private CourseManager $courseManager;
    private ExamBlockManager $ebManager;
    
    public function __construct(CourseManager $courseManager,
            ExamBlockManager $ebManager) {
        $this->middleware(["auth","verified"]);
        $this->courseManager = $courseManager;
        $this->ebManager = $ebManager;
    }
    
    public function index(Course $course){
        $this->authorize("viewAny", $course);
        
        $courseData = $this->courseManager->getCourseFullDepth($course->id);
        if(is_null($courseData)){
            return redirect(route("courseIndex"))->with("error",__("Could not found Course."));
        }
        
        return view("courses.details", [
            "course" => $courseData
        ]);
    }
    
    public function post(Course $course){
        $this->authorize("create", Course::class);

        $attributes = $this->attributeValidation();
        $ebInfo = new NewExamBlockInfo(
                $attributes["maxExams"],
                $attributes["cfu"],
                $attributes["courseYear"]);
        
        $examBlock = $this->ebManager->saveExamBlock($ebInfo, $course->id);
        
        return Response::view("components.courses.exam-block-row",
                ["examBlock" => $examBlock]);
        
    }
    
    public function delete(ExamBlock $examblock){
        $this->authorize("delete", Course::class);
        
        $this->ebManager->deleteExamBlock($examblock->id);
        
        return back();
    }
    
    public function put(ExamBlock $examblock){
        $this->authorize("create", Course::class);
        $attributes = $this->attributeValidation();
        $ebInfo = new NewExamBlockInfo(
                $attributes["maxExams"],
                $attributes["cfu"],
                $attributes["courseYear"]);

        $editedExamBlock = $this->ebManager
                    ->updateExamBlock($ebInfo, $examblock->id);
        
        return Response::view("components.courses.exam-block-header",
                ["examBlock" => $editedExamBlock]);
    }
     
    private function attributeValidation(): array{
        $validationRules = [
            "cfu" => ["required", "numeric"],
            "courseYear" => ["nullable", "numeric"],
            "maxExams" => ["required","numeric"],
         ]; 
        $validator = Validator::make(request()->all(), $validationRules);
        if($validator->fails()){
            throw new ValidationException($validator, 
                ControllerHelpers::flashResponse(
                    $validator->errors()->all(), 422));
        }
        return $validator->getData();
    }
    
}
