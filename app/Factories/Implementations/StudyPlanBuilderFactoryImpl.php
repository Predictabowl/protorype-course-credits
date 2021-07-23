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
use App\Factories\Interfaces\FrontManagerFactory;
use App\Factories\Interfaces\CourseManagerFactory;

/**
 * Description of StudyPlanBuilderFactoryImpl
 *
 * @author piero
 */
class StudyPlanBuilderFactoryImpl implements StudyPlanBuilderFactory {

    private $frontFactory;
    private $courseFactory;
    
    public function __construct() {
        $this->frontFactory = app()->make(FrontManagerFactory::class);
        $this->courseFactory = app()->make(CourseManagerFactory::class);
    }
    
    public function getStudyPlanBuilder($frontId, $courseId): StudyPlanBuilder {
        $frontManager = $this->frontFactory->getFrontManager($frontId);
        $courseManager = $this->courseFactory->getCourseManager($courseId);
        return new StudyPlanBuilderImpl($frontManager, $courseManager);
    }

}
