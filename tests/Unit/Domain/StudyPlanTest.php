<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;
use App\Domain\ExamBlockDTO;
use App\Domain\StudyPlan;
use App\Domain\LinkedTakenExam;

/**
 * Description of StudyPlanTest
 *
 * @author piero
 */
class StudyPlanTest extends TestCase{

    private $studyPlan;

    protected function setUp(): void {
        $this->studyPlan = new StudyPlan();
    }

    public function test_addExamLik_when_no_exam_present() {
        $block = new ExamBlockDTO(1,1);
        $option = new ExamOptionDTO(1,"option1", $block, 12, "ssd");
        $taken = new LinkedTakenExam(new TakenExamDTO(1,"taken1", "ssd", 9));
        $takenPk = $taken->getTakenExam()->getId();

        $this->assertEmpty($this->studyPlan->getExams());
        
        $leftover = $this->studyPlan->addExamLink($option, $taken);
        
        $this->assertCount(1, $this->studyPlan->getExams());
        $this->assertEquals(0, $leftover->getActualCfu());
        $this->assertEquals($option, $this->studyPlan->getExam($option->getId())
                ->getExamOption());
        $this->assertEquals($taken->getTakenExam(),
                $this->studyPlan->getExam($option->getId())
                ->getTakenExam($takenPk)->getTakenExam());
    }
    
    public function test_addExamLink_leftover_cfu_values() {
        $block = new ExamBlockDTO(1,2);
        $option1 = new ExamOptionDTO(1,"option1", $block, 12, "ssd");
        $option2 = new ExamOptionDTO(2,"option2", $block, 12, "ssd");
        $taken1 = new LinkedTakenExam(new TakenExamDTO(1,"taken1", "ssd", 10));
        $taken2 = new LinkedTakenExam(new TakenExamDTO(2,"taken2", "ssd", 6));

        $this->studyPlan->addExamLink($option1, $taken1);
        $this->studyPlan->addExamLink($option1, $taken2);
        
        $this->assertEquals(4, $taken2->getActualCfu());
        
        $this->studyPlan->addExamLink($option2, $taken2);
        
        $this->assertEquals(0, $this->studyPlan->getExam($option1->getId())
                ->getIntegrationValue());
        $this->assertEquals(8, $this->studyPlan->getExam($option2->getId())
                ->getIntegrationValue());
    }
    
    public function test_getIntegrationValue_with_empty_studyPlan() {
        $integration = $this->studyPlan->getIntegrationValue();
        
        $this->assertEquals(0, $integration);
    }
    
    public function test_getIntegrationValue() {
        $block1 = new ExamBlockDTO(1,2);
        $block2 = new ExamBlockDTO(2,1);
        $option1 = new ExamOptionDTO(1,"option1", $block1, 9, "ssd1");
        $option2 = new ExamOptionDTO(2,"option2", $block1, 12, "ssd2");
        $option3 = new ExamOptionDTO(3,"option3", $block2, 18, "ssd1");
        $taken1 = new LinkedTakenExam(new TakenExamDTO(1,"taken1", "ssd1", 10));
        $taken2 = new LinkedTakenExam(new TakenExamDTO(2,"taken2", "ssd2", 6));
        $taken3 = new LinkedTakenExam(new TakenExamDTO(2,"taken2", "ssd1", 9));
        
        $this->studyPlan->addExamLink($option1, $taken1);
        $this->studyPlan->addExamLink($option2, $taken2);
        $this->studyPlan->addExamLink($option3, $taken3);
        
        $value = $this->studyPlan->getIntegrationValue();
        
        $this->assertEquals(15, $value);
    }
    
}
