<?php

namespace Tests\Unit\Domain;

/** This technically an integration test, but it's more work mocking
 * than using the DTO
*/
use App\Domain\ApprovedExam;
use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;
use App\Domain\LinkedTakenExam;
use App\Domain\TakenExamDTO;
use PHPUnit\Framework\TestCase;

class ApprovedExamTest extends TestCase
{
    
    protected function setUp(): void
    {
        //parent::setUp();
        //$this->seed(\Database\Seeders\DatabaseSeederTest::class);
    }


    public function test_Integration_value()
    {
        $option = $this->createOption(12);
        $sut = new ApprovedExam($option);
        $sut->addTakenExam($this->createTakenExam(3,"taken1"));
        $sut->addTakenExam($this->createTakenExam(2,"taken2"));

        $this->assertEquals(7,$sut->getIntegrationValue());
    }

    public function test_takenExam_is_not_added_if_Integration_is_zero(){
        $option = $this->createOption(12);
        $sut = new ApprovedExam($option);
        
        $takenExam1 = $this->createTakenExam(12,"taken1");
        $sut->addTakenExam($takenExam1);
        $takenExam2 = $this->createTakenExam(2,"taken2");
        
        $this->assertEquals($takenExam2,$sut->addTakenExam($takenExam2));
        $this->assertCount(1,$sut->getTakenExams());
        $this->assertEquals($takenExam1->getTakenExam(),
                $sut->getTakenExams()[$takenExam1->getTakenExam()->getPK()]->getTakenExam());
    }

    public function test_addTakenExam_is_split_if_cfu_value_is_too_high(){
        $option = $this->createOption(12);
        $sut = new ApprovedExam($option);
        $takenExam = $sut->addTakenExam($this->createTakenExam(13));
        
        $this->assertEquals(1,$takenExam->getActualCfu());
        $this->assertEquals(0,$sut->getIntegrationValue());
    }


    private function createOption($cfu = 12): ExamOptionDTO
    {
        return new ExamOptionDTO("test", new ExamBlockDTO(1), $cfu,"ssd");
    }
    
    private function createTakenExam($cfu = 9, $name = "taken", $actual = null): LinkedTakenExam
    {
        return new LinkedTakenExam(new TakenExamDTO($name,"ssd",$cfu), $actual);
    }
   
}
