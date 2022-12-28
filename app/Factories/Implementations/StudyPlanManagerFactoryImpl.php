<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\StudyPlanManagerFactory;
use App\Models\Front;
use App\Services\Implementations\StudyPlanManagerImpl;
use App\Services\Interfaces\StudyPlanManager;
use App\Services\Interfaces\UserFrontManager;
use App\Services\Interfaces\YearCalculator;
use function app;

/**
 * Description of StudyPlanManagerFactoryImpl
 *
 * @author piero
 */
class StudyPlanManagerFactoryImpl implements StudyPlanManagerFactory{
    
    public function get(Front $front): StudyPlanManager {
        return new StudyPlanManagerImpl($front,
                app()->make(UserFrontManager::class),
                app()->make(YearCalculator::class));
    }

}
