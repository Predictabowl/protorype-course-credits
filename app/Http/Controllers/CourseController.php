<?php

namespace App\Http\Controllers;

use App\Exceptions\Custom\CourseNameAlreadyExistsException;
use App\Models\Course;
use App\Services\Interfaces\CourseManager;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use function back;
use function redirect;
use function request;
use function route;
use function view;


class CourseController extends Controller{

    private CourseManager $courseManager;
    
    public function __construct(CourseManager $courseManager) {
        $this->middleware(["auth","verified"]);
        $this->courseManager = $courseManager;
    }
    
    public function index() {
        $this->authorize("viewAny", Course::class);
        
        $filters = request(["search"]);
        return view("courses.index", [
            "courses" => $this->courseManager->getAllCourses($filters)
        ]);
     }
     
     public function updateCourseForm(Course $course) {
        $this->authorize("view", $course);
        
        return view("courses.input",[
            "course" => $course,
            "action" => route("courseUpdate",[$course->id])]);
     }
     
    public function newCourseForm() {
        $this->authorize("create", Course::class);
        
        return view("courses.input",["action" => route("courseCreate")]);
    }
    
    public function post() {
         $this->authorize("create", Course::class);
         
         $attributes = $this->attributeValidation();
         try{
            $this->courseManager->addCourse(new Course($attributes));
         } catch (CourseNameAlreadyExistsException $ex){
            throw ValidationException::withMessages(["name" => $ex->getMessage()]);
         }
         return redirect(route("courseIndex"))
                 ->with("success","Aggiunto: ".$attributes["name"]);
    }
     
    public function delete(int $courseId){
         $this->authorize("delete", Course::class);
         
         $this->courseManager->removeCourse($courseId);
         return back();
    }
     
    public function put(Course $course){
         $this->authorize("update", $course);
         $attributes = $this->attributeValidation();
         $newCourse = new Course($attributes);
         $newCourse->id = $course->id;
         
         try{
            $this->courseManager->updateCourse($newCourse);
         } catch (CourseNameAlreadyExistsException $ex){
             throw ValidationException::withMessages(["name" => $ex->getMessage()]);
         }
         
         return redirect(route("courseShow",[$course->id]))
                 ->with("success","Aggiornato: ".$newCourse->name);
    }
    
    public function activate(int $courseId){
         $this->authorize("create", Course::class);
         $active = (request("active") != null) ? true : false;
         
         $this->courseManager->setCourseActive($courseId, $active);
         
         return Response::noContent();
    }    
     
    private function attributeValidation(): array{
         return request()->validate([
            "name" => ["required", "string"],
            "cfu" => ["required", "numeric"],
            "maxRecognizedCfu" => ["nullable", "numeric"],
            "otherActivitiesCfu" => ["nullable", "numeric"],
            "finalExamCfu" => ["required","numeric"],
            "numberOfYears" => ["required", "numeric"],
            "cfuTresholdForYear" => ["numeric"]
         ]);
     }
    
}
