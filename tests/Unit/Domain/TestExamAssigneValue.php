<?php

namespace App\Domain;

use App\Models\TakenExam;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TestExamAssigneValue extends TestCase
{
    const FIXTURE_MAX_CFU = 12;

    private $takenExam;

    protected  function setUp(): void
    {
        $this->takenExam = new TakenExam();
        $this->takenExam->setAttribute("id",1);
        $this->takenExam->setAttribute("name","Diritto Privato");
        $this->takenExam->setAttribute("ssd_id","IUS/01");
        $this->takenExam->setAttribute("front_id",1);
        $this->takenExam->setAttribute("cfu",self::FIXTURE_MAX_CFU);        
    }

    public function test_auto_assigned_cfu_when_not_set()
    {
        $sut = new ExamAssignedValue($this->takenExam);
        $this->assertEquals($sut->getCfuValue(),self::FIXTURE_MAX_CFU);
    }

    public function test_override_cfu_value()
    {
        $sut = new ExamAssignedValue($this->takenExam, 9);
        $this->assertEquals($sut->getCfuValue(),9);
    }

    public function test_exception_expected_when_cfu_value_exceed_base_value()
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new ExamAssignedValue($this->takenExam, self::FIXTURE_MAX_CFU+1);
    }

    public function test_exception_expected_when_cfu_value_is_set_too_low()
    {
        $sut = new ExamAssignedValue($this->takenExam);
        $this->expectException(InvalidArgumentException::class);
        $sut->setCfuValue(0);
    }
}
