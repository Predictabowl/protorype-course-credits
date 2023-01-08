<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\UserFrontManager;

class StudentController extends Controller
{
    private UserFrontManager $userFrontManager;
    
    public function __construct(UserFrontManager $userFrontManager) {
        $this->middleware(["auth","verified"]);
        $this->userFrontManager = $userFrontManager;
    }
    
    public function showFront()
    {   
        $frontManager = $this->userFrontManager->getFrontManager();
        return redirect()->route("frontView", [$frontManager->getFront()]);

    }

}
