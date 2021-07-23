<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;

/**
 * Description of ExamBlockLinkerTest
 *
 * @author piero
 */
class ExamBlockDTOTest extends TestCase{
    
    const FIXTURE_CFU = 12;
    const FIXTURE_NUM_EXAMS = 2;
    
    private $block;
    private $exams;
    
    protected function setUp(): void {
        $this->block=  new ExamBlockDTO(1,self::FIXTURE_NUM_EXAMS);
        $this->exams[0] = new ExamOptionDTO(1,"first", $this->block, self::FIXTURE_CFU, "ssd1");
        $this->exams[1] = new ExamOptionDTO(2,"second", $this->block, self::FIXTURE_CFU, "ssd2");
        $this->exams[2] = new ExamOptionDTO(3,"third", $this->block, self::FIXTURE_CFU, "ssd3");
    }
    
    
    public function test_getCfuValue(){
        $cfu = $this->block->getCfu();
        
        $this->assertEquals(self::FIXTURE_CFU*self::FIXTURE_NUM_EXAMS, $cfu);
    }
    
    public function test_getIntegrationValue_with_no_taken_exams(){
        $taken1 = new TakenExamDTO(1, "nome 1", "ssd1", 15);
        $taken2 = new TakenExamDTO(2, "nome 2", "ssd2", 6);
        
        $value = $this->block->getIntegrationValue();
        
        $this->assertEquals(self::FIXTURE_CFU*self::FIXTURE_NUM_EXAMS, $value);
    }
    
    public function test_getIntegrationValue(){
        $taken1 = new TakenExamDTO(1, "nome 1", "ssd1", 15);
        $taken2 = new TakenExamDTO(2, "nome 2", "ssd2", 6);
        $this->exams[0]->addTakenExam($taken1);
        $this->exams[1]->addTakenExam($taken2);
        
        $value = $this->block->getIntegrationValue();
        
        $this->assertEquals(self::FIXTURE_CFU*self::FIXTURE_NUM_EXAMS -18, $value);
    }
    
    public function test_getSlotsAvailable_when_empty(){
        $value = $this->block->getNumSlotsAvailable();
        
        $this->assertEquals(2, $value);
    }
    
    public function test_getSlotsAvailable_when_one_is_available(){
        $taken1 = new TakenExamDTO(1, "nome 1", "ssd1", 15);
        $this->exams[1]->addTakenExam($taken1);
        
        $value = $this->block->getNumSlotsAvailable();
        
        $this->assertEquals(1, $value);
    }
    
    // if the result ends ne negative the it's an invalid state, but should not 
    // be the job of this sut to address that.
    public function test_getSlotsAvailable_when_non_is_available(){
        $taken1 = new TakenExamDTO(1, "nome 1", "ssd1", 15);
        $this->exams[1]->addTakenExam($taken1);
        $taken2 = new TakenExamDTO(2, "nome 2", "ssd2", 5);
        $this->exams[2]->addTakenExam($taken1);
        
        $value = $this->block->getNumSlotsAvailable();
        
        $this->assertEquals(0, $value);
    }
}
