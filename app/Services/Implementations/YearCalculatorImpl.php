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
use Carbon\Carbon;

/**
 * This implementation is based solely on the Recognized CFU
 *
 * @author piero
 */
class YearCalculatorImpl implements YearCalculator{
    
    private $treshold;
    
    public function __construct(int $treshold = 40) {
        $this->treshold = $treshold;
    }

    public function getCourseYear(Course $course, StudyPlan $plan): int {
        $cfu = $plan->getRecognizedCredits();
        return min(($cfu / $this->treshold)+1, $course->numberOfYears);
    }
    
    public function setTreshold(int $treshold): YearCalculatorImpl {
        $this->treshold = $treshold;
    }
    
    public function getTreshold(): int {
        return $this->treshold;
    }

    public function getAcademicYear(Carbon $date): int {
        $month = $date->format("m");
        $year = $date->format("Y");
        if($month < 4){
            $year--;
        }
        return $year;
    }

}
