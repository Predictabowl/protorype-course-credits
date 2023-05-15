<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Factories\Interfaces\StudyPlanManagerFactory;
use App\Models\Front;
use App\Services\Implementations\StudyPlanManagerImpl;
use App\Services\Interfaces\StudyPlanManager;
use App\Services\Interfaces\YearCalculator;

/**
 * Description of StudyPlanManagerFactoryImpl
 *
 * @author piero
 */
class StudyPlanManagerFactoryImpl implements StudyPlanManagerFactory{
    
    private StudyPlanBuilderFactory $studyPlanBuilderFactory;
    private YearCalculator $yearCalcolator;
    
    public function __construct(StudyPlanBuilderFactory $studyPlanBuilderFactory,
            YearCalculator $yearCalcolator) {
        $this->studyPlanBuilderFactory = $studyPlanBuilderFactory;
        $this->yearCalcolator = $yearCalcolator;
    }

        public function get(Front $front): StudyPlanManager {
        return new StudyPlanManagerImpl($front, $this->studyPlanBuilderFactory,
                $this->yearCalcolator);
    }

}
