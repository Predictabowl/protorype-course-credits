<?php

namespace Tests\Unit\Domain;

use App\Domain\DeclaredExam;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DeclaredExamTest extends TestCase
{

    public function test_auto_assigned_distributed_cfu_when_not_set()
    {
        $sut = new DeclaredExam("test name","ssd",11);
        $this->assertEquals($sut->getDistributedCfu(),11);
    }

    public function test_throw_if_max_cfu_value_is_negative()
    {
        $this->expectException(InvalidArgumentException::class);
        new DeclaredExam("test name","ssd",-1);
    }

    public function test_throw_when_distribute_cfu_value_exceed_max_value()
    {
        $this->expectException(InvalidArgumentException::class);
        new DeclaredExam("test name","ssd",12,13);
    }

    public function test_throw_when_distributed_cfu_value_is_negative()
    {
        $this->expectException(InvalidArgumentException::class);
        new DeclaredExam("test name","ssd",12,-1);
    }

    public function test_throw_when_max_cfu_is_set_lower_than_distributed()
    {
        $sut = new DeclaredExam("test name","ssd",12,10);
        $this->expectException(InvalidArgumentException::class);
        $sut->setMaxCfu(9);
    }

    public function test_split()
    {
        $sut = new DeclaredExam("test name","ssd",12);
        $sut2 = $sut->split(5);

        $this->assertEquals(
            [$sut->getName(),$sut->getMaxCfu(),$sut->getSsd()],
            [$sut2->getName(),$sut2->getMaxCfu(),$sut2->getSsd()]);
        $this->assertEquals($sut->getDistributedCfu(),7);
        $this->assertEquals($sut2->getDistributedCfu(),5);
    }
}
