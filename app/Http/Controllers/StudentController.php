<?php

namespace App\Http\Controllers;

use App\Factories\Interfaces\UserFrontManagerFactory;
use function auth;
use function redirect;

class StudentController extends Controller
{
    private UserFrontManagerFactory $ufManagerFactory;
    
    public function __construct(UserFrontManagerFactory $ufManagerFactory) {
        $this->middleware(["auth","verified"]);
        $this->ufManagerFactory = $ufManagerFactory;
    }
    
    public function showFront()
    {   
        $frontManager = $this->ufManagerFactory
                ->get(auth()->user()->id)->getFrontManager();
        return redirect()->route("frontView", [$frontManager->getFront()]);

    }

}
