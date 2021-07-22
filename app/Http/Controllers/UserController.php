<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware(["auth","verified"]);
        $this->middleware("can:viewAny,App/Models/Front");
    }

    public function index() {
        
    }

}