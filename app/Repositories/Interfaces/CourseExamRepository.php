<?php

namespace App\Repositories\Interfaces;

use App\Models\Course;
use App\Models\CourseExam;
use App\Models\Exam;

interface CourseExamRepository {

	public function create(Course $course, Exam $exam): CourseExam;
}