<?php

namespace App\Service\Interfaces;

use App\Models\Course;
use App\Models\CourseExam;
use App\Models\Exam;

interface ExamManager {

	public function attach(Exam $exam, Course $course);
}