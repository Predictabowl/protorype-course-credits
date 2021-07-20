<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Factories\Interfaces\ManagersFactory;

/**
 * Description of StudyPlanBuilderFactoryImpl
 *
 * @author piero
 */
class StudyPlanBuilderFactoryImpl implements StudyPlanBuilderFactory {

    private $managersFactory;
    
    public function __construct() {
        $this->managersFactory = app()->make(ManagersFactory::class);
    }
    
    public function getStudyPlanBuilder($frontId, $courseId): StudyPlanBuilder {
        $frontManager = $this->managersFactory->getFrontManager($frontId);
        $courseManager = $this->managersFactory->getCourseManager($courseId);
        return new StudyPlanBuilderImpl($frontManager, $courseManager);
    }

}
