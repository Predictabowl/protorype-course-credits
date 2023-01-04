<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\ExamBlockStudyPlanDTO;
use App\Domain\ExamOptionStudyPlanDTO;
use App\Domain\TakenExamDTO;

/**
 * Description of ExamBlockLinkerTest
 *
 * @author piero
 */
class ExamBlockDTOTest extends TestCase{
    
    const FIXTURE_CFU = 12;
    const FIXTURE_NUM_EXAMS = 2;
    const FIXTURE_COURSE_YEAR = 3;
    
    private $block;
    private $exams;
    
    protected function setUp(): void {
        $this->block=  new ExamBlockStudyPlanDTO(1,self::FIXTURE_NUM_EXAMS, self::FIXTURE_CFU, self::FIXTURE_COURSE_YEAR);
        $this->exams[0] = new ExamOptionStudyPlanDTO(1,"first", $this->block, "ssd1");
        $this->exams[1] = new ExamOptionStudyPlanDTO(2,"second", $this->block, "ssd2");
        $this->exams[2] = new ExamOptionStudyPlanDTO(3,"third", $this->block, "ssd3");
    }
    
    
    public function test_getTotalCfuValue(){
        $cfu = $this->block->getTotalCfu();
        
        $this->assertEquals(self::FIXTURE_CFU*self::FIXTURE_NUM_EXAMS, $cfu);
    }
    
    public function test_getIntegrationValue_with_no_taken_exams(){
        $taken1 = new TakenExamDTO(1, "nome 1", "ssd1", 15, 25);
        $taken2 = new TakenExamDTO(2, "nome 2", "ssd2", 6, 26);
        
        $value = $this->block->getIntegrationValue();
        
        $this->assertEquals(self::FIXTURE_CFU*self::FIXTURE_NUM_EXAMS, $value);
    }
    
    public function test_getIntegrationValue(){
        $taken1 = new TakenExamDTO(1, "nome 1", "ssd1", 15, 30);
        $taken2 = new TakenExamDTO(2, "nome 2", "ssd2", 6, 27);
        $this->exams[0]->addTakenExam($taken1);
        $this->exams[1]->addTakenExam($taken2);
        
        $value = $this->block->getIntegrationValue();
        
        $this->assertEquals(self::FIXTURE_CFU*self::FIXTURE_NUM_EXAMS -18, $value);
    }
    
    public function test_getRecognizedCredits(){
        $taken1 = new TakenExamDTO(1, "nome 1", "ssd1", 15, 25);
        $taken2 = new TakenExamDTO(2, "nome 2", "ssd2", 6, 22);
        $this->exams[0]->addTakenExam($taken1);
        $this->exams[1]->addTakenExam($taken2);
        
        $value = $this->block->getRecognizedCredits();
        
        $this->assertEquals(18, $value);
    }
    
    public function test_getSlotsAvailable_when_empty(){
        $value = $this->block->getNumSlotsAvailable();
        
        $this->assertEquals(2, $value);
    }
    
    public function test_getSlotsAvailable_when_one_is_available(){
        $taken1 = new TakenExamDTO(1, "nome 1", "ssd1", 15, 28);
        $this->exams[1]->addTakenExam($taken1);
        
        $value = $this->block->getNumSlotsAvailable();
        
        $this->assertEquals(1, $value);
    }
    
    // if the result ends negative the it's an invalid state, but should not 
    // be the job of this sut to address that.
    public function test_getSlotsAvailable_when_none_is_available(){
        $taken1 = new TakenExamDTO(1, "nome 1", "ssd1", 15, 20);
        $this->exams[1]->addTakenExam($taken1);
        $taken2 = new TakenExamDTO(2, "nome 2", "ssd2", 5, 22);
        $this->exams[2]->addTakenExam($taken1);
        
        $value = $this->block->getNumSlotsAvailable();
        
        $this->assertEquals(0, $value);
    }
    
    public function test_serialize(){
        $block1 = new ExamBlockStudyPlanDTO(3, 2, 10, 1);
        $option1 = new ExamOptionStudyPlanDTO(5, "option 1", $block1, "ssd1");
        $option2 = new ExamOptionStudyPlanDTO(7, "option 2", $block1, "ssd5");
        
        $string = serialize($block1);
        $result = unserialize($string);
        
        $this->assertInstanceOf(ExamBlockStudyPlanDTO::class, $result);
        $this->assertEquals($block1, $result);
        $this->assertNotSame($block1, $result);
    }
}
