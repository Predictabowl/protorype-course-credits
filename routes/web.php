<?php

use App\Http\Controllers\TakenExamController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\StudentController;
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

require __DIR__.'/auth.php';


//-------------- Front

Route::get("/front",[FrontController::class,"index"])->name("frontIndex");
Route::get("/front/{front}",[FrontController::class,"show"])->name("frontView");
Route::put("/front/{front}",[FrontController::class,"put"]); //change course for the post


//-------------- Taken exams

Route::post("/front/exam/{front}",[TakenExamController::class,"create"])->name("postTakenExam");
Route::delete("/front/exam/{front}",[TakenExamController::class,"delete"])->name("deleteTakenExam");


//-------------- Study Plan
Route::get("/studyplan/{front}",[StudyPlanController::class,"show"])->name("studyPlan");
Route::get("/studyplan/pdf/{front}",[StudyPlanController::class,"createPdf"])->name("studyPlanPdf");


//-------------- Users
Route::get("/user",[UserController::class,"index"])->name("userIndex");
Route::get("/user/{user}",[UserController::class,"show"])->name("userShow");
Route::delete("/user/{user}",[UserController::class,"delete"])->name("userDelete");
Route::get("/userUpdate/{user}",[UserController::class,"updateView"])->name("userUpdate");
Route::put("/userUpdate/{user}",[UserController::class,"put"]);

//-------------- User Roles
Route::put("/userRole/{user}",[UserController::class,"putRoles"])->name("userRoleUpdate");

//-------------- Auto routing endpoints.
// Will redirect to the Front routes, but these will automatically create 
// the database entry for the authenticated user Front if is missing.
// May think to remove it later and make FrontController handle everything
// when the admin side is completely ironed out.

Route::get("/student/front",[StudentController::class,"showFront"])->name("frontPersonal");
