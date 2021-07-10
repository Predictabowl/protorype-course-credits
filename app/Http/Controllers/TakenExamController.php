<?php

namespace App\Http\Controllers;

use App\Models\Front;
use App\Models\TakenExam;
use Illuminate\Http\Request;

class TakenExamController extends Controller
{
    public function index()
    {
        $exams = TakenExam::all()->where("front_id","==",auth()->user()->front->id);

        return view("exams.takenexams",[
            "exams" =>  $exams
        ]);
    }
}
