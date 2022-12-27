<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\CourseAdminManager;
use function app;


class CourseController extends Controller{

    public function __construct() {
        $this->middleware(["auth","verified"]);
    }
    
    public function index() {
         $this->authorize("viewAny", auth()->user());
         
        return view("courses.index", [
            "courses" => $this->getCourseManager()->getAll()
        ]);
     }
     
     private function getCourseManager(): CourseAdminManager{
         return app()->make(CourseAdminManager::class);
     }
}
