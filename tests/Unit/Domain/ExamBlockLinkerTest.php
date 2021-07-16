<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\ExamBlockLinker;
use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;

/**
 * Description of ExamBlockLinkerTest
 *
 * @author piero
 */
class ExamBlockLinkerTest extends TestCase{
    
    private $blockLinker;
    private $exams;
    
    protected function setUp(): void {
        $block = new ExamBlockDTO(1,2);
        $this->exams[0] = new ExamOptionDTO(1,"first", $block, 12, "ssd1");
        $this->exams[1] = new ExamOptionDTO(2,"second", $block, 9, "ssd2");
        $this->exams[2] = new ExamOptionDTO(3,"third", $block, 10, "ssd3");
        $block->addOption($this->exams[0]);
        $block->addOption($this->exams[1]);
        $block->addOption($this->exams[2]);
        $this->blockLinker = new ExamBlockLinker($block);
    }
    
    public function test_first_link() {
        $pk = $this->exams[0]->getId();
        
        $this->assertTrue($this->blockLinker->linkExam($pk));
        $this->assertEquals($this->exams[0], $this->blockLinker->getLinkedExams()[$pk]);
    }
    
    public function test_multiple_of_same_link() {
        $pk = $this->exams[0]->getId();
        $this->blockLinker->linkExam($pk);
        
        $this->assertTrue($this->blockLinker->linkExam($pk));
        $this->assertEquals($this->exams[0],$this->blockLinker->getLinkedExams()[$pk]);
    }
    
    public function test_link_limit_exceeded() {
        $pk1 = $this->exams[0]->getId();
        $pk2 = $this->exams[1]->getId();
        $pk3 = $this->exams[2]->getId();
        
        $this->assertTrue($this->blockLinker->linkExam($pk1));
        $this->assertTrue($this->blockLinker->linkExam($pk2));
        $this->assertFalse($this->blockLinker->linkExam($pk3));
        $this->assertCount(2,$this->blockLinker->getLinkedExams());
        $this->assertContains($this->exams[0], $this->blockLinker->getLinkedExams());
        $this->assertContains($this->exams[1], $this->blockLinker->getLinkedExams());
        $this->assertNotContains($this->exams[2], $this->blockLinker->getLinkedExams());
    }
    
}
