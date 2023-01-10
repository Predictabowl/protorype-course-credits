<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\StudyPlanManagerFactory;
use App\Factories\Interfaces\UserFrontManagerFactory;
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
    
    private UserFrontManagerFactory $ufManagerFactory;
    private YearCalculator $yearCalcolator;
    
    public function __construct(UserFrontManagerFactory $ufManagerFactory,
            YearCalculator $yearCalcolator) {
        $this->ufManagerFactory = $ufManagerFactory;
        $this->yearCalcolator = $yearCalcolator;
    }

    public function get(Front $front): StudyPlanManager {
        return new StudyPlanManagerImpl($front, $this->ufManagerFactory,
                $this->yearCalcolator);
    }

}
