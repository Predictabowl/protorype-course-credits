<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Implementations;

use App\Services\Interfaces\YearCalculator;
use App\Models\Course;
use App\Domain\StudyPlan;

/**
 * This implementation is based solely on the Recognized CFU
 *
 * @author piero
 */
class YearCalculatorImpl implements YearCalculator{
    /**
     * This implementation only use the recognized credits from the study plan.
     * 
     * @param Course $course
     * @param StudyPlan $plan
     * @return int
     */
    public function getCourseYear(Course $course, StudyPlan $plan): int {
        $cfu = $plan->getRecognizedCredits();
        return min(($cfu / $course->cfuTresholdForYear)+1, $course->numberOfYears);
    }
    
    /**
     * This implementation ignores the day.
     * 
     * @param int $day
     * @param int $month
     * @param int $year
     * @return int
     */
    public function getAcademicYear(int $day, int $month, int $year): int {
        if($month < 4){
            $year--;
        }
        return $year;
    }

}
