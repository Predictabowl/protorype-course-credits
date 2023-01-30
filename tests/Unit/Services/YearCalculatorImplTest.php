<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Models\Course;
use App\Domain\StudyPlan;
use App\Services\Implementations\YearCalculatorImpl;
use Carbon\Carbon;

/**
 * Description of YearCalculatorImplTest
 *
 * @author piero
 */
class YearCalculatorImplTest extends TestCase{
    
    const FIXTURE_NUM_YEARS = 3;
    const FIXTURE_TRESHOLD = 40;
    
    private YearCalculatorImpl $sut;
   
    protected function setUp(): void {
        parent::setUp();
        $this->sut = new YearCalculatorImpl();
    }

    public function test_getCourseYear_whenThresholdIsZero_shouldAlwaysReturnFirstYear(){
        $plan = $this->createMock(StudyPlan::class);  
        $plan->expects($this->once())
                ->method("getRecognizedCredits")
                ->willReturn(120);
        
        $course = new Course([
            "name" => "test Course",
            "numberOfYears" => self::FIXTURE_NUM_YEARS,
            "cfuTresholdForYear" => 0
        ]);
        
        $this->assertEquals(1,$this->sut->getCourseYear($course, $plan));
    }
    
    public function test_getCourseYear_whenThresholdIsNull_shouldAlwaysReturnFirstYear(){
        $plan = $this->createMock(StudyPlan::class);  
        $plan->expects($this->once())
                ->method("getRecognizedCredits")
                ->willReturn(120);
        
        $course = new Course([
            "name" => "test Course",
            "numberOfYears" => self::FIXTURE_NUM_YEARS,
            "cfuTresholdForYear" => null
        ]);
        
        $this->assertEquals(1,$this->sut->getCourseYear($course, $plan));
    }
        
    public function test_getCourseYear(){
        $plan = $this->createMock(StudyPlan::class);  
        $plan->expects($this->exactly(7))
                ->method("getRecognizedCredits")
                ->willReturnOnConsecutiveCalls(0,39,40,79,80,119,120);
        
        $course = new Course([
            "name" => "test Course",
            "numberOfYears" => self::FIXTURE_NUM_YEARS,
            "cfuTresholdForYear" => self::FIXTURE_TRESHOLD
        ]);
        
        
        $this->assertEquals(1,$this->sut->getCourseYear($course, $plan));
        $this->assertEquals(1,$this->sut->getCourseYear($course, $plan));
        $this->assertEquals(2,$this->sut->getCourseYear($course, $plan));
        $this->assertEquals(2,$this->sut->getCourseYear($course, $plan));
        $this->assertEquals(3,$this->sut->getCourseYear($course, $plan));
        $this->assertEquals(3,$this->sut->getCourseYear($course, $plan));
        $this->assertEquals(3,$this->sut->getCourseYear($course, $plan));
    }
    
    public function test_getAcademicYear(){
        
        $this->assertEquals(2021, $this->sut->getAcademicYear(12,8,2021));
        $this->assertEquals(2022, $this->sut->getAcademicYear(9,4,2022));
        $this->assertEquals(2021, $this->sut->getAcademicYear(28,3,2022));
    }
}
