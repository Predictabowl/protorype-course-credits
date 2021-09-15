<?php

namespace Tests\Unit\Domain;

use App\Domain\TakenExamDTO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TakenExamDTOTest extends TestCase
{
    const FIXTURE_CFU = 12;


    public function test_auto_assigned_actual_cfu_when_not_set()
    {
        $sut = new TakenExamDTO(1,"name1","ssd1", self::FIXTURE_CFU, 18, 2);
        $this->assertEquals(self::FIXTURE_CFU, $sut->getActualCfu());
        $this->assertEquals(self::FIXTURE_CFU, $sut->getCfu());
        $this->assertEquals(18, $sut->getGrade());
        $this->assertEquals(2, $sut->getCourseYear());
    }

    public function test_throw_when_actual_cfu_value_exceed_max_value()
    {
        $this->expectException(\InvalidArgumentException::class);
        new TakenExamDTO(1,"name","ssd",10, 20, 1, 11);
    }
    
    public function test_throw_when_actual_cfu_value_is_negative()
    {
        $this->expectException(InvalidArgumentException::class);
        new TakenExamDTO(3,"name","ssd",10, 20, 2, -1);
    }

    public function test_split()
    {
        //$exam = ;
        $exam1 = new TakenExamDTO(4,"name","ssd", self::FIXTURE_CFU, 20, 1);
        $exam2 = $exam1->split(5);

        $this->assertNotSame($exam1,$exam2);
        $this->assertEquals(self::FIXTURE_CFU-5, $exam1->getActualCfu());
        $this->assertEquals(5, $exam2->getActualCfu());
        $exam1->setActualCfu(5);
        $this->assertEquals($exam1, $exam2);
    }
    
    public function test_serialize(){
        $exam = new TakenExamDTO(7, "taken", "code1", 12, 26, 3);
        
        $string = serialize($exam);
        $result = unserialize($string);
        
        $this->assertInstanceOf(TakenExamDTO::class, $result);
        $this->assertEquals($exam, $result);
        $this->assertNotSame($exam, $result);
    }
}
