<?php

namespace Tests\Unit\Domain;

use App\Domain\LinkedTakenExam;
use App\Domain\TakenExamDTO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LinkedTakenExamTest extends TestCase
{
    const FIXTURE_CFU = 12;


    public function test_auto_assigned_actual_cfu_when_not_set()
    {
        $sut = new LinkedTakenExam(new TakenExamDTO("name1","ssd1", self::FIXTURE_CFU));
        $this->assertEquals($sut->getActualCfu(),self::FIXTURE_CFU);
    }

    public function test_throw_when_actual_cfu_value_exceed_max_value()
    {
        $this->expectException(InvalidArgumentException::class);
        new LinkedTakenExam(new TakenExamDTO("name","ssd",10),11);
    }
    
    public function test_throw_when_actual_cfu_value_is_not_integer()
    {
        $this->expectException(InvalidArgumentException::class);
        new LinkedTakenExam(new TakenExamDTO("name","ssd",10),"test");
    }

    public function test_throw_when_actual_cfu_value_is_negative()
    {
        $this->expectException(InvalidArgumentException::class);
        new LinkedTakenExam(new TakenExamDTO("name","ssd",10),-1);
    }

    public function test_split()
    {
        //$exam = ;
        $sut = new LinkedTakenExam(new TakenExamDTO("name","ssd", self::FIXTURE_CFU));
        $sut2 = $sut->split(5);

        $this->assertEquals($sut->getTakenExam(),$sut2->getTakenExam());
        $this->assertEquals(self::FIXTURE_CFU-5, $sut->getActualCfu());
        $this->assertEquals(5, $sut2->getActualCfu());
    }
}
