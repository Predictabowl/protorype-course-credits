<?php

namespace Tests\Unit\Domain;

/** This technically an integration test, but it's more work mocking
 * than using the DTO
*/
use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;
use PHPUnit\Framework\TestCase;

class ExamOptionDTOTest extends TestCase
{

    public function test_Integration_value()
    {
        $option = $this->createOption(12);
        $option->addTakenExam($this->createTakenExam(3,"taken1"));
        $option->addTakenExam($this->createTakenExam(2,"taken2"));
        
        $this->assertEquals(7,$option->getIntegrationValue());
    }

    public function test_takenExam_is_not_added_if_Integration_is_zero(){
        $option = $this->createOption(12);
        
        $takenExam1 = $this->createTakenExam(12,"taken1");
        $option->addTakenExam($takenExam1);
        $takenExam2 = $this->createTakenExam(2,"taken2");
        
        $this->assertEquals($takenExam2,$option->addTakenExam($takenExam2));
        $this->assertCount(1,$option->getTakenExams());
        $this->assertEquals(0,$takenExam1->getActualCfu());
        $takenExam1->setActualCfu(12);
        $this->assertNotSame($takenExam1, 
                $option->getTakenExams()[$takenExam1->getId()]);
        $this->assertEquals($takenExam1,
                $option->getTakenExams()[$takenExam1->getId()]);
    }

    public function test_addTakenExam_is_split_if_cfu_value_is_too_high(){
        $option = $this->createOption(12);
        $takenExam = $option->addTakenExam($this->createTakenExam(13));
        
        $this->assertEquals(1,$takenExam->getActualCfu());
        $this->assertEquals(0,$option->getIntegrationValue());
    }
    
    public function test_addTakenExam_is_not_added_if_actual_cfu_is_0(){
        $option = $this->createOption(12);
        $takenExam = $option->addTakenExam($this->createTakenExam(12,"name",0));

        $this->assertEquals(12,$option->getIntegrationValue());
        $this->assertEmpty($option->getTakenExams());
    }
    
    public function test_addTakenExam_is_not_added_if_block_is_full(){
        $block = new ExamBlockDTO(1,2);
        $option1 = new ExamOptionDTO(1, "name 1", $block, 12, "ssd1");
        $option2 = new ExamOptionDTO(2, "name 2", $block, 12, "ssd2");
        $option3 = new ExamOptionDTO(3, "name 3", $block, 12, "ssd3");
        
        $takenExam1 = $option1->addTakenExam($this->createTakenExam(9,"name 1"));
        $takenExam2 = $option2->addTakenExam($this->createTakenExam(9,"name 2"));
        $takenExam3 = $this->createTakenExam(9,"name 3");
        
        $result = $option3->isTakenExamAddable($takenExam3);
        $result2 =  $option3->addTakenExam($takenExam3);

        $this->assertFalse($result);
        $this->assertEmpty($option3->getTakenExams());
    }


    private function createOption($cfu = 12): ExamOptionDTO
    {
        return new ExamOptionDTO(1,"test", new ExamBlockDTO(1,2), $cfu,"ssd");
    }
    
    private function createTakenExam($cfu = 9, $name = "taken", $actual = null): TakenExamDTO
    {
        return new TakenExamDTO($name,$name,"ssd",$cfu, $actual);
    }
   
}
