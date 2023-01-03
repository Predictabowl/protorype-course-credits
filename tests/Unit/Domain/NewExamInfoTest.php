<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Domain;

use App\Domain\NewExamInfo;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Description of ExamBlockLinkerTest
 *
 * @author piero
 */
class NewExamInfoTest extends TestCase{
    
    public function test_ssd_shouldBeUpcased(){
        $exam = new NewExamInfo("test name", "inf/01");
        
        $this->assertEquals("INF/01", $exam->getSsd());
    }
    
    public function test_ssd_wrongFormat_shouldThrow(){
        $this->expectException(InvalidArgumentException::class);
        $exam = new NewExamInfo("test name", "INF-/01");
    }
}
