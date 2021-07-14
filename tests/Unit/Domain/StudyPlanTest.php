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
    
}
