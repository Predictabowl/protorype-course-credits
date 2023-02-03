<?php

namespace App\Http\Controllers;

use App\Domain\NewExamBlockInfo;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Services\Interfaces\CourseAdminManager;
use Illuminate\Support\Facades\Response;
use function __;
use function back;
use function ddd;
use function redirect;
use function request;
use function route;
use function view;

class ExamBlockController extends Controller
{
    private CourseAdminManager $courseManager;
    
    public function __construct(CourseAdminManager $courseManager) {
        $this->middleware(["auth","verified"]);
        $this->courseManager = $courseManager;
    }
    
    public function index(Course $course){
        $this->authorize("viewAny", $course);
        
        $courseData = $this->courseManager->getCourseFullData($course->id);
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
        
        $examBlock = $this->courseManager->saveExamBlock($ebInfo, $course->id);
        
//        return back()->with("success",__("Added Exam Block"));
        return Response::view("components.courses.exam-block-row",
                ["examBlock" => $examBlock]);
        
    }
    
    public function delete(ExamBlock $examblock){
        $this->authorize("delete", Course::class);
        
        $this->courseManager->deleteExamBlock($examblock->id);
        
        return back();
    }
    
    public function put(ExamBlock $examblock){
        $this->authorize("create", Course::class);
        $attributes = $this->attributeValidation();
        $ebInfo = new NewExamBlockInfo(
                $attributes["maxExams"],
                $attributes["cfu"],
                $attributes["courseYear"]);
        try{
            $this->courseManager->updateExamBlock($ebInfo, $examblock->id);
        } catch (ExamBlockNotFoundException $ex) {
            return back()->with("error",__("Missing Entity"));
        }
        
        return back();
    }
    
    private function attributeValidation(): array{
         return request()->validate([
            "cfu" => ["required", "numeric"],
            "courseYear" => ["nullable", "numeric"],
            "maxExams" => ["required","numeric"],
         ]);
     }
    
}
