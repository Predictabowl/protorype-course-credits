<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\CourseDataBuilderFactory;
use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Services\Interfaces\CourseManager;
use App\Services\Interfaces\ExamDistance;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\StudyPlanBuilder;

/**
 * Description of StudyPlanBuilderFactoryImpl
 *
 * @author piero
 */
class StudyPlanBuilderFactoryImpl implements StudyPlanBuilderFactory {

    private FrontManager $frontManager;
    private CourseDataBuilderFactory $courseDataBuilderFactory;
    private ExamDistance $examDistance;
    private CourseManager $courseManager;
    
    public function __construct(FrontManager $frontManager,
            CourseManager $courseManager,
            CourseDataBuilderFactory $courseDataBuilderFactory,
            ExamDistance $examDistance) {
        $this->frontManager = $frontManager;
        $this->courseDataBuilderFactory = $courseDataBuilderFactory;
        $this->examDistance = $examDistance;
        $this->courseManager = $courseManager;
    }


    public function get(int $frontId, int $courseId): StudyPlanBuilder {
        $course = $this->courseManager->getCourseFullDepth($courseId);
        return new StudyPlanBuilderImpl(
                $this->frontManager->getTakenExams($frontId),
                $this->courseDataBuilderFactory->get($course),
                $this->examDistance);
    }

}
