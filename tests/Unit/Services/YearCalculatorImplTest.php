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

    
    public function test_getCourseYear(){
        $plan = $this->createMock(StudyPlan::class);  
        $plan->expects($this->exactly(7))
                ->method("getRecognizedCredits")
                ->willReturnOnConsecutiveCalls(0,39,40,79,80,119,120);
        
        $course = new Course([
            "name" => "test Course",
            "numberOfYears" => self::FIXTURE_NUM_YEARS,
        ]);
        
        $calculator = new YearCalculatorImpl();
        
        $this->assertEquals(1,$calculator->getCourseYear($course, $plan));
        $this->assertEquals(1,$calculator->getCourseYear($course, $plan));
        $this->assertEquals(2,$calculator->getCourseYear($course, $plan));
        $this->assertEquals(2,$calculator->getCourseYear($course, $plan));
        $this->assertEquals(3,$calculator->getCourseYear($course, $plan));
        $this->assertEquals(3,$calculator->getCourseYear($course, $plan));
        $this->assertEquals(3,$calculator->getCourseYear($course, $plan));
    }
    
    public function test_getAcademicYear(){
        $calculator = new YearCalculatorImpl();
        
        $date = Carbon::createFromFormat("d/m/Y","16/08/2021");
        $this->assertEquals(2021, $calculator->getAcademicYear($date));
        
        $date = Carbon::createFromFormat("d/m/Y","01/04/2022");
        $this->assertEquals(2022, $calculator->getAcademicYear($date));
        
        $date = Carbon::createFromFormat("d/m/Y","31/03/2022");
        $this->assertEquals(2021, $calculator->getAcademicYear($date));
    }
}
