<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Interfaces\UserFrontManager;

class StudentController extends Controller
{
    public function __construct() {
        $this->middleware(["auth","verified"]);
    }
    
    public function show()
    {   
        $manager = $this->getUserFrontManager();
            return view("front.show",[
                "exams" => $manager->getFrontManager()->getTakenExams()
            ]);

    }
    
    private function getUserFrontManager(): UserFrontManager {
        return app()->make(UserFrontManager::class);
    }
}
