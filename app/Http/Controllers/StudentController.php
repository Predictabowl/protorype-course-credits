<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\UserFrontManager;

class StudentController extends Controller
{
    public function __construct() {
        $this->middleware(["auth","verified"]);
    }
    
    public function showFront()
    {   
        $frontManager = app()->make(UserFrontManager::class)->getFrontManager();
        return redirect()->route("frontView", [$frontManager->getFront()]);

    }

}
