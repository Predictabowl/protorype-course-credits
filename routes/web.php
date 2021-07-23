<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\TakenExamController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\StudentController;
use App\Models\Course;
use App\Http\Controllers\StudyPlanController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth',"verified"])->name('dashboard');


Route::get("/testpage",function(){
        $course = Course::with("examBlocks.examBlockOptions.exam.ssd",
                "examBlocks.examBlockOptions.ssds")->find(1);
        return view("testpage");
    }); //only for tests

require __DIR__.'/auth.php';

//------------------

Route::get('/admindashboard', [AdminController::class,"show"])->name('adminDashboard');

Route::get("/exams",[ExamController::class,"index"]); //only for testing purposes

//Route::get("/front",[FrontController::class,"index"])->name("frontIndex");

Route::get("/front",[FrontController::class,"index"])->name("frontIndex");

Route::get("/front/{front}",[FrontController::class,"show"])->name("frontView");

Route::put("/front/{front}",[FrontController::class,"put"]); //change course for the post

Route::get("/front/options",[FrontController::class,"getOptions"]) //test round
        ->name("courseOptions");

Route::post("/front/row",[TakenExamController::class,"create"]);

Route::delete("/front/row",[TakenExamController::class,"delete"]);

Route::get("/tests", function(){
    $course  = Course::first()->with("examBlocks.examBlockOptions.examApproved")->get();
    $result = $course ->first()->examBlocks->map(fn($block) => $block->examBlockOptions)
        ->flatten()->filter(fn ($option) => $option->examApproved->getAttribute("ssd_id") == 7);
    ddd($result);

    return $result;
});

Route::get("/studyplan/{front}",[StudyPlanController::class,"show"])->name("studyPlan");

Route::get("/student/front",[StudentController::class,"showFront"])->name("frontPersonal");

Route::get("/student/studyplan",[StudentController::class,"showStudyPlan"])->name("studyPlanPersonal");
