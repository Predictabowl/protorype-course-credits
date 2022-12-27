<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\CourseManagerFactory;
use App\Factories\Interfaces\FrontManagerFactory;
use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Services\Interfaces\ExamDistance;
use App\Services\Interfaces\StudyPlanBuilder;
use function app;

/**
 * Description of StudyPlanBuilderFactoryImpl
 *
 * @author piero
 */
class StudyPlanBuilderFactoryImpl implements StudyPlanBuilderFactory {

    private FrontManagerFactory $frontFactory;
    private CourseManagerFactory $courseFactory;
    
    public function __construct(FrontManagerFactory $frontFactory, 
            CourseManagerFactory $courseFactory) {
        $this->frontFactory = $frontFactory;
        $this->courseFactory = $courseFactory;
    }
    
    public function getStudyPlanBuilder($frontId, $courseId): StudyPlanBuilder {
        $frontManager = $this->frontFactory->getFrontManager($frontId);
        $courseManager = $this->courseFactory->getCourseManager($courseId);
        return new StudyPlanBuilderImpl($frontManager, $courseManager, 
                app()->make(ExamDistance::class));
    }

}
