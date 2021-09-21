<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Implementations;

use App\Services\Interfaces\StudyPlanManager;
use App\Domain\StudyPlan;
use App\Services\Interfaces\YearCalculator;
use App\Models\Front;
use App\Services\Interfaces\UserFrontManager;
use Carbon\Carbon;

/**
 * Description of StudyPlanManagerImpl
 *
 * @author piero
 */
class StudyPlanManagerImpl implements StudyPlanManager{
    
    private $front;
    private $plan;
    private $yearCalculator;
    
    public function __construct(Front $front) {
        $this->front = $front;
        $this->plan = null;
        $this->yearCalculator = app()->make(YearCalculator::class);
    }
    
    public function getStudyPlan(): ?StudyPlan {
        if (!isset($this->plan)){
            $this->plan = $this->buildStudyPlan();
        }
        return $this->plan;
    }
    
    private function buildStudyPlan(): ?StudyPlan{
        $builder = app()->make(UserFrontManager::class)
                ->setUserId($this->front->user_id)
                ->getStudyPlanBuilder();
        if (!isset($builder)){
            return null;
        }
        return $builder->getStudyPlan();
    }

    public function getAcademicYear(): int {
        $date = Carbon::now();
        return $this->yearCalculator->getAcademicYear(
                $date->format("d"),
                $date->format("m"),
                $date->format("Y")
            );
    }

    public function getCourseYear(): ?int {
        $studyPlan = $this->getStudyPlan();
        if( $studyPlan == null){
            return null;
        }
        return $this->yearCalculator->getCourseYear($this->front->course,$studyPlan);
    }

}
