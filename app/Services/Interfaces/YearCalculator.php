<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Interfaces;

use Carbon\Carbon;
use App\Models\Course;
use App\Domain\StudyPlan;

/**
 *
 * @author piero
 */
interface YearCalculator {
    
    public function getCourseYear(Course $course, StudyPlan $plan): int;
    
    public function getAcademicYear(Carbon $date): int;
    
}
