<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\TakenExamController;
use App\Http\Controllers\FrontController;
use App\Models\Course;
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
    /*
    $s1 = "Diritto Amministrativo";
    $s2 = "DIritto Amministrativo II";
    $l = levenshtein(strtolower($s1), strtolower($s2));
    ddd($l);*/
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth',"verified"])->name('dashboard');

require __DIR__.'/auth.php';

//------------------

Route::get("/exams",[ExamController::class,"index"]); //only for testing purposes

Route::get("/front",[FrontController::class,"index"])->middleware("auth");

Route::post("/front/row",[TakenExamController::class,"create"])->middleware("auth");

Route::delete("/front/row",[TakenExamController::class,"delete"])->middleware("auth");

Route::get("/tests", function(){
    $course  = Course::first()->with("examBlocks.examBlockOptions.examApproved")->get();
    // $result = $course ->first()->examBlocks->map(fn ($block) => $block->examBlockOptions
    //     ->map(fn ($option) => $option->examApproved))->flatten();
    $result = $course ->first()->examBlocks->map(fn($block) => $block->examBlockOptions)
        ->flatten()->filter(fn ($option) => $option->examApproved->getAttribute("ssd_id") == 7);
    ddd($result);

    return $result;
});
