<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\FrontManager;
use function auth;
use function redirect;

class StudentController extends Controller
{
    private FrontManager $frontManager;
    
    public function __construct(FrontManager $frontManager) {
        $this->middleware(["auth","verified"]);
        $this->frontManager = $frontManager;
    }
    
    public function showFront()
    {   
        return redirect()->route("frontView", [
            $this->frontManager->getOrCreateFront(auth()->user()->id)
        ]);

    }

}
