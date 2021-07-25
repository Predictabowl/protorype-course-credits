<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\TakenExamController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\StudentController;
use App\Models\Course;
use App\Http\Controllers\StudyPlanController;
use App\Http\Controllers\UserController;
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

Route::get("/exams",[ExamController::class,"index"]); //only for testing purposes



//-------------- Front

Route::get("/front",[FrontController::class,"index"])->name("frontIndex");

Route::get("/front/{front}",[FrontController::class,"show"])->name("frontView");

Route::put("/front/{front}",[FrontController::class,"put"]); //change course for the post


//-------------- Taken exams

Route::post("/front/exam/{front}",[TakenExamController::class,"create"])->name("postTakenExam");

Route::delete("/front/exam/{front}",[TakenExamController::class,"delete"])->name("deleteTakenExam");


Route::get("/studyplan/{front}",[StudyPlanController::class,"show"])->name("studyPlan");


//-------------- Users
Route::get("/user",[UserController::class,"index"])->name("userIndex");
Route::get("/user/{user}",[UserController::class,"show"])->name("userShow");
Route::put("/user/{user}",[UserController::class,"put"])->name("userUpdate");
Route::delete("/user/{user}",[UserController::class,"delete"])->name("userDelete");

//-------------- Auto routing endpoints.
// Will redirect to the Front routes, but these will automatically create 
// the database entry for the authenticated user Front if is missing.
// May think to remove it later and make FrontController handle everything
// when the admin side is completely ironed out.

Route::get("/student/front",[StudentController::class,"showFront"])->name("frontPersonal");
