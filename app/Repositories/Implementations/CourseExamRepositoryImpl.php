<?php

namespace App\Repositories\Implementations;

use App\Models\Course;
use App\Models\CourseExam;
use App\Models\Exam;
use App\Repositories\Interfaces\CourseExamRepository;

/**
 * 
 */
class CourseExamRepositoryImpl  implements  CourseExamRepository
{
	
	function __construct()
	{
		// code...
	}

	public function create(Course $course, Exam $exam): CourseExam
	{
		return CourseExam::firstOrCreate([
			"course_id" => $course->id,
			"exam_id" => $exam->id
		]);
	}
}