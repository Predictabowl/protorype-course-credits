<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller{
    
    public function __construct() {
        $this->middleware(["auth","verified"]);
    }
    
     public function index() {
         $this->authorize("viewAny", auth()->user());
         
        return view("courses.index", [
            "courses" => $this->getCourseManager()->getAll(request(["search"]))
        ]);
     }
     
    private function getCourseManager(): CourseManager{
        return app()->make(CourseManager::class);
    }
}
