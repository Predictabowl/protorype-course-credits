<?php

namespace App\Service\Implementations;

use App\Models\Course;
use App\Models\CourseExam;
use App\Models\Exam;
use App\Service\Interfaces\ExamManager;
use Illuminate\Support\Facades\DB;

/**
 * 
 */
class ExamManagerImpl  implements  ExamManager
{
	
	function __construct()
	{
		// code...
	}

	public function attach(Exam $exam, Course $course)
	{
		$ec = DB::table("course_exam")
			->where("course_id",$course->id)
			->where("exam_id",$exam->id)
			->get();
		if($ec->isEmpty()){
			
			$exam->courses()->attach($course);
		}
	}
}