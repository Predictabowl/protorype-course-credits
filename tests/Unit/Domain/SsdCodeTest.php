<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Domain;

use App\Domain\SsdCode;
use App\Exceptions\Custom\InvalidInputException;
use Tests\TestCase;

/**
 * Description of ExamBlockLinkerTest
 *
 * @author piero
 */
class SsdCodeTest extends TestCase{
    
    public function test_ssd_shouldBeUpcased(){
        $exam = new SsdCode("inf/01");
        
        $this->assertEquals("INF/01", $exam->getCode());
    }
    
    public function test_ssd_wrongFormat_shouldThrow(){
        $this->expectException(InvalidInputException::class);
        $exam = new SsdCode("IUS-/01");
    }
    
}
