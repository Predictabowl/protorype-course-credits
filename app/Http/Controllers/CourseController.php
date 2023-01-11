<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\Interfaces\CoursesAdminManager;
use function back;
use function request;
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
    
    public function post() {
         $this->authorize("create", Course::class);
         
         $attributes = $this->attributeValidation();
         $this->coursesManager->addCourse(new Course($attributes));
         return back()->with("success","Aggiunto: ".$attributes["name"]);
     }
     
     public function delete(Course $course){
         $this->authorize("delete", Course::class);
         
         $this->coursesManager->removeCourse($course->id);
         return back()->with("success","Eliminato: ".$course->name);
     }
     
     public function put(Course $course){
         $this->authorize("update", $course);
         $attributes = $this->attributeValidation();
         $newCourse = new Course($attributes);
         $newCourse->id = $course->id;
         
         $this->coursesManager->updateCourse($newCourse);
         
         return back()->with("success","Aggiornato: ".$attributes["name"]);
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
