<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\CourseDataBuilderFactory;
use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Models\Course;
use App\Services\Implementations\StudyPlanBuilderImpl;
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
    
    public function __construct(FrontManager $frontManager,
            CourseDataBuilderFactory $courseDataBuilderFactory,
            ExamDistance $examDistance) {
        $this->frontManager = $frontManager;
        $this->courseDataBuilderFactory = $courseDataBuilderFactory;
        $this->examDistance = $examDistance;
    }


    public function get(int $frontId, Course $course): StudyPlanBuilder {
        return new StudyPlanBuilderImpl(
                $this->frontManager->getTakenExams($frontId),
                $this->courseDataBuilderFactory->get($course),
                $this->examDistance);
    }

}
