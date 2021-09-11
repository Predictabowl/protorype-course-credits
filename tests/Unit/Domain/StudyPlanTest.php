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
        $block = new ExamBlockDTO(1,1,12);
        $option = new ExamOptionDTO(1,"option1", new ExamBlockDTO(2,1,12), "ssd");
        $taken = new TakenExamDTO(1,"taken1", "ssd", 9);
        $takenPk = $taken->getId();
        
        $studyPlan = new StudyPlan(collect([$block]));
        
        $this->expectException(\InvalidArgumentException::class);
        
        $studyPlan->addExamLink($option, $taken);
    }
    
    public function test_addExamLink_leftover_cfu_values() {
        $block = new ExamBlockDTO(1,2,12);
        $option1 = new ExamOptionDTO(1,"option1", $block, "ssd");
        $option2 = new ExamOptionDTO(2,"option2", $block, "ssd");
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
        $block1 = new ExamBlockDTO(1,2,9);
        $block2 = new ExamBlockDTO(2,1,18);
        $option1 = new ExamOptionDTO(1,"option1", $block1, "ssd1");
        $option2 = new ExamOptionDTO(2,"option2", $block1, "ssd2");
        $option3 = new ExamOptionDTO(3,"option3", $block2, "ssd1");
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
    
    
    public function test_getMaxCfu_when_not_declared_should_be_null(){
        $plan = new StudyPlan(collect([]));
        
        $this->assertNull($plan->getMaxCfu());
    }
    
    public function test_getMaxCfu_when_declared(){
        $plan = new StudyPlan(collect([]),5);
        
        $this->assertEquals(5,$plan->getMaxCfu());
    }
    
    
    public function test_getLeftOverAllottedCfu() {
        $block1 = $this->createMock(ExamBlockDTO::class);
        $block2 = $this->createMock(ExamBlockDTO::class);
        
        $block1->expects($this->once())
                ->method("getRecognizedCredits")
                ->willReturn(10);
        $block1->expects($this->once())
                ->method("getId")
                ->willReturn(1);
        $block2->expects($this->once())
                ->method("getRecognizedCredits")
                ->willReturn(6);
        $block2->expects($this->once())
                ->method("getId")
                ->willReturn(2);        
        
        $studyPlan = new StudyPlan(collect([$block1, $block2]),30);
        ;
        $value = $studyPlan->getLeftoverAllottedCfu();
        
        $this->assertEquals(30-10-6, $value);
    }
    
    public function test_getLeftOverAllottedCfu_when_max_is_not_defined_should_be_null() {
        $studyPlan = new StudyPlan(collect([]));
                
        $value = $studyPlan->getLeftoverAllottedCfu();
        
        $this->assertNull($value);
    }
    
    public function test_addExamLink_with_max_cfu() {
        $block1 = $this->createMock(ExamBlockDTO::class);
        $option = $this->createMock(ExamOptionDTO::class);
        $exam = $this->createMock(TakenExamDTO::class);
        $macCfu = 15;
        $recognizedCfu = 5;
        
        $block1->expects($this->once())
                ->method("getExamOptions")
                ->willReturn(collect([$option]));
        $block1->expects($this->exactly(2))
                ->method("getId")
                ->willReturn(1);
        $block1->expects($this->once())
                ->method("getRecognizedCredits")
                ->willReturn($recognizedCfu);
        $option->expects($this->exactly(2))
                ->method("getId")
                ->willReturn(2);
        $option->expects($this->once())
                ->method("addTakenExam")
                ->with($exam,$macCfu-$recognizedCfu);
        $option->expects($this->once())
                ->method("getBlock")
                ->willReturn($block1);
        
        $studyPlan = new StudyPlan(collect([$block1]),$macCfu);
        
        $value = $studyPlan->addExamLink($option,$exam);
        
    }
    
}
