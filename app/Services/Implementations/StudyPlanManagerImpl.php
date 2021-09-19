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

/**
 * Description of StudyPlanManagerImpl
 *
 * @author piero
 */
class StudyPlanManagerImpl implements StudyPlanManager{
    
    private $front;
    private $plan;
    
    public function __construct(Front $front) {
        $this->front = $front;
        $this->plan = $this->buildStudyPlan();
    }
    
    public function getStudyPlan(): ?StudyPlan {
        return $this->plan;
    }

    public function getYearCalculator(): YearCalculator {
        
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

}
