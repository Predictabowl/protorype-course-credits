<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Domain;

use App\Models\Course;
use PHPUnit\Framework\TestCase;
use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;
use App\Domain\ExamBlockDTO;
use App\Domain\StudyPlan;

/**
 * Description of StudyPlanTest
 *
 * @author piero
 */
class StudyPlanTest extends TestCase{

    
    public function test_addExamLik_when_exam_not_present_in_the_course() {
        $block = new ExamBlockDTO(1,1);
        $option = new ExamOptionDTO(1,"option1", new ExamBlockDTO(2,1), 12, "ssd");
        $taken = new TakenExamDTO(1,"taken1", "ssd", 9);
        $takenPk = $taken->getId();
        
        $studyPlan = new StudyPlan(collect([$block]));
        
        $this->expectException(\InvalidArgumentException::class);
        
        $studyPlan->addExamLink($option, $taken);
    }
    
    public function test_addExamLink_leftover_cfu_values() {
        $block = new ExamBlockDTO(1,2);
        $option1 = new ExamOptionDTO(1,"option1", $block, 12, "ssd");
        $option2 = new ExamOptionDTO(2,"option2", $block, 12, "ssd");
        $taken1 = new TakenExamDTO(1,"taken1", "ssd", 10);
        $taken2 = new TakenExamDTO(2,"taken2", "ssd", 6);
        
        $studyPlan = new StudyPlan(collect([$block]));

        $studyPlan->addExamLink($option1, $taken1);
        $studyPlan->addExamLink($option1, $taken2);
        
        $this->assertEquals(4, $taken2->getActualCfu());
        
        $studyPlan->addExamLink($option2, $taken2);
        
        $this->assertEquals(0, $studyPlan->getExam($option1->getId())
                ->getIntegrationValue());
        $this->assertEquals(8, $studyPlan->getExam($option2->getId())
                ->getIntegrationValue());
    }
    
    public function test_getRecognizedCredits_with_empty_studyPlan() {
        $studyPlan = new StudyPlan(collect([]));
        $integration = $studyPlan->getRecognizedCredits();
        
        $this->assertEquals(0, $integration);
    }
    
    public function test_getRecognizedCredits() {
        $block1 = new ExamBlockDTO(1,2);
        $block2 = new ExamBlockDTO(2,1);
        $option1 = new ExamOptionDTO(1,"option1", $block1, 9, "ssd1");
        $option2 = new ExamOptionDTO(2,"option2", $block1, 12, "ssd2");
        $option3 = new ExamOptionDTO(3,"option3", $block2, 18, "ssd1");
        $taken1 = new TakenExamDTO(1,"taken1", "ssd1", 10);
        $taken2 = new TakenExamDTO(2,"taken2", "ssd2", 6);
        $taken3 = new TakenExamDTO(2,"taken2", "ssd1", 9);
        
        $studyPlan = new StudyPlan(collect([$block1,$block2]));
        
        $studyPlan->addExamLink($option1, $taken1);
        $studyPlan->addExamLink($option2, $taken2);
        $studyPlan->addExamLink($option3, $taken3);
        
        $value = $studyPlan->getRecognizedCredits();
        
        $this->assertEquals(24, $value);
    }
    
}
