<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Interfaces;

use App\Models\Course;
use App\Domain\StudyPlan;

/**
 *
 * @author piero
 */
interface YearCalculator {
    
    /**
     * Return the year of enrollment within a specific degree course based on the
     * study plan given.
     * 
     * @param Course $course
     * @param StudyPlan $plan
     * @return int
     */
    public function getCourseYear(Course $course, StudyPlan $plan): int;
    
    /**
     * Return the academic year in course based on the date given.
     * 
     * @param int $day
     * @param int $month
     * @param int $year
     * @return int
     */
    public function getAcademicYear(int $day, int $month, int $year): int;
    
}
