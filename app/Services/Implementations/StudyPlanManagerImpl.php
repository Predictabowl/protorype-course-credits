<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Implementations;

use App\Domain\StudyPlan;
use App\Factories\Interfaces\UserFrontManagerFactory;
use App\Models\Front;
use App\Services\Interfaces\StudyPlanManager;
use App\Services\Interfaces\YearCalculator;
use Carbon\Carbon;

/**
 * Description of StudyPlanManagerImpl
 *
 * @author piero
 */
class StudyPlanManagerImpl implements StudyPlanManager{

    private YearCalculator $yearCalc;
    private Front $front;
    private $plan;
    private UserFrontManagerFactory $ufManagerFactory;
    
    public function __construct(Front $front,
            UserFrontManagerFactory $ufManagerFactory,
            YearCalculator $yearCalc) {
        $this->front = $front;
        $this->yearCalc = $yearCalc;
        $this->ufManagerFactory = $ufManagerFactory;
    }
    
    public function getStudyPlan(): ?StudyPlan {
        if (!isset($this->plan)){
            $this->plan = $this->buildStudyPlan();
        }
        return $this->plan;
    }
    
    private function buildStudyPlan(): ?StudyPlan{
        $builder = $this->ufManagerFactory->get($this->front->user_id)
                ->getStudyPlanBuilder();
        if (!isset($builder)){
            return null;
        }
        return $builder->getStudyPlan();
    }

    public function getAcademicYear(): int {
        $date = Carbon::now();
        return $this->yearCalc->getAcademicYear(
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
        return $this->yearCalc->getCourseYear($this->front->course,$studyPlan);
    }

}
