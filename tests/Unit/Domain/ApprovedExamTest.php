<?php

namespace Tests\Unit\Domain;

use App\Domain\ApprovedExam;
use App\Domain\DeclaredExam;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ApprovedExamTest extends TestCase
{
    const FIXTURE_CFU = 12;


    protected function setUp(): void
    {
        
    }

    public function test_throw_if_cfu_not_positive()
    {
        $this->expectException(InvalidArgumentException::class);
        new ApprovedExam("test",0);
    }

    public function test_Integration_value()
    {
        $sut = new ApprovedExam("test name",self::FIXTURE_CFU);

        $this->assertEquals($sut->addDeclaredExams(new DeclaredExam("1",5)),
            self::FIXTURE_CFU-5);

        $sut->addDeclaredExams(new DeclaredExam("2",3));
        $this->assertEquals($sut->getIntegrationValue(),self::FIXTURE_CFU-5-3);
    }

    public function test_declaredExam_is_not_added_if_cfu_value_is_too_high(){
        $sut = new ApprovedExam("test name",self::FIXTURE_CFU);
        
        $this->assertEquals($sut->addDeclaredExams(new DeclaredExam("1",self::FIXTURE_CFU+1)),-1);
        $this->assertEmpty($sut->getDeclaredExams());   
    }

   
}
