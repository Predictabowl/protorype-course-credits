<?php

namespace App\Http\Controllers;

use App\Exceptions\Custom\CourseNameAlreadyExistsException;
use App\Models\Course;
use App\Services\Interfaces\CoursesAdminManager;
use Illuminate\Validation\ValidationException;
use function back;
use function redirect;
use function request;
use function route;
use function view;


class CourseController extends Controller{

    private CoursesAdminManager $coursesManager;
    
    public function __construct(CoursesAdminManager $coursesManager) {
        $this->middleware(["auth","verified"]);
        $this->coursesManager = $coursesManager;
    }
    
    public function index() {
        $this->authorize("viewAny", Course::class);
        
        $filters = request(["search"]);
        return view("courses.index", [
            "courses" => $this->coursesManager->getAllCourses($filters)
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
            $this->coursesManager->addCourse(new Course($attributes));
         } catch (CourseNameAlreadyExistsException $ex){
            throw ValidationException::withMessages(["name" => $ex->getMessage()]);
         }
         return redirect(route("courseIndex"))
                 ->with("success","Aggiunto: ".$attributes["name"]);
     }
     
     public function delete(int $courseId){
         $this->authorize("delete", Course::class);
         
         $this->coursesManager->removeCourse($courseId);
         return back();
     }
     
     public function put(Course $course){
         $this->authorize("update", $course);
         $attributes = $this->attributeValidation();
         $newCourse = new Course($attributes);
         $newCourse->id = $course->id;
         
         try{
            $this->coursesManager->updateCourse($newCourse);
         } catch (CourseNameAlreadyExistsException $ex){
             throw ValidationException::withMessages(["name" => $ex->getMessage()]);
         }
         
         return redirect(route("courseShow",[$course->id]))
                 ->with("success","Aggiornato: ".$newCourse->name);
     }
     
     private function attributeValidation(): array{
         return request()->validate([
            "name" => ["required", "string"],
            "cfu" => ["required", "numeric"],
            "maxRecognizedCfu" => ["numeric"],
            "otherActivitiesCfu" => ["numeric"],
            "finalExamCfu" => ["required","numeric"],
            "numberOfYears" => ["required", "numeric"],
            "cfuTresholdForYear" => ["required", "numeric"]
         ]);
     }
    
}
