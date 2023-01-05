<?php

namespace Tests\Unit\Domain;

/** This technically an integration test, but it's more work mocking
 * than using the DTO
*/
use App\Domain\ExamBlockStudyPlanDTO;
use App\Domain\ExamStudyPlanDTO;
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
        $this->assertEquals(5,$option->getRecognizedCredits());
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
        $block = new ExamBlockStudyPlanDTO(1,2,12, 1);
        $option1 = new ExamStudyPlanDTO(1, "name 1", $block, "ssd1");
        $option2 = new ExamStudyPlanDTO(2, "name 2", $block, "ssd2");
        $option3 = new ExamStudyPlanDTO(3, "name 3", $block, "ssd3");
        
        $takenExam1 = $option1->addTakenExam($this->createTakenExam(9,"name 1"));
        $takenExam2 = $option2->addTakenExam($this->createTakenExam(9,"name 2"));
        $takenExam3 = $this->createTakenExam(9,"name 3");
        
        $result = $option3->isTakenExamAddable($takenExam3);
        $result2 =  $option3->addTakenExam($takenExam3);

        $this->assertFalse($result);
        $this->assertEmpty($option3->getTakenExams());
    }

    public function test_addTakenExam_when_maxCfu_is_set(){
        $option1 = $this->createOption(12);
        $takenExam = $this->createTakenExam(9,"name 1");
        
        $result = $option1->isTakenExamAddable($takenExam);
        $result2 =  $option1->addTakenExam($takenExam,7);

        $this->assertTrue($result);
        $this->assertEquals(2,$result2->getActualCfu());
        $this->assertEquals(5,$option1->getIntegrationValue());
    }
    
    
    public function test_serialization_ok(){
        $block = new ExamBlockStudyPlanDTO(3, 1, 9, 2);
        $option = new ExamStudyPlanDTO(5, "test", $block, "ssd1");
        $taken1 = new TakenExamDTO(7, "taken 1", "ssd3", 3, 18);
        $taken2 = new TakenExamDTO(11, "taken 2", "ssd5", 4, 20);
        $option->addTakenExam($taken1);
        $option->addTakenExam($taken2);
        
        $string = serialize($option);
        $result = unserialize($string);
        
        $this->assertInstanceOf(ExamStudyPlanDTO::class, $result);
        $result->setBlock($block);
        $this->assertEquals($option,$result);
    }
    
    public function test_serialization_invalid_state(){
        $block = new ExamBlockStudyPlanDTO(3, 1, 9, null);
        $option = new ExamStudyPlanDTO(5, "test", $block, "ssd1");
        
        $string = serialize($option);
        $result = unserialize($string);
        
        $this->expectException(\App\Exceptions\Custom\InvalidStateException::class);
        $result->getBlock();
    }

    private function createOption($cfu = 12): ExamStudyPlanDTO
    {
        return new ExamStudyPlanDTO(1,"test", new ExamBlockStudyPlanDTO(1,2,$cfu,2),"ssd");
    }
    
    private function createTakenExam($cfu = 9, $name = "taken", $actual = null): TakenExamDTO
    {
        return new TakenExamDTO($name,$name,"ssd",$cfu, 22, null, $actual);
    }
   
}
