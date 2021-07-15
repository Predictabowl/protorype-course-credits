<?php

namespace Tests\Unit\Services;

use App\Domain\ExamOptionDTO;
use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Services\Interfaces\ExamDistance;
use App\Services\Interfaces\FrontManager;
use PHPUnit\Framework\TestCase;

class StudyPlanBuilderImplTest extends TestCase
{
    private $frontManager;
    private $examDistance;
    private $planBuilder;
    private $takenExams;
    private $blocks;
    private $options;

    protected function setUp(): void
    {
        $this->frontManager = $this->createMock(FrontManager::class);
        $this->examDistance = $this->createMock(ExamDistance::class);
        
        $this->setupData();
        
        $this->planBuilder = new StudyPlanBuilderImpl($this->frontManager, $this->examDistance);
    }
    
    private function setupData() {
        $this->takenExams = collect([
            new TakenExamDTO(1,"Diritto Privato","IUS/01",9),
            new TakenExamDTO(2,"Istituzione di diritto","IUS/09",6),
            new TakenExamDTO(3,"Diritto Commerciale mod I","IUS/04",5),
            new TakenExamDTO(4,"Diritto Commerciale mod II","IUS/07",5),
            new TakenExamDTO(5,"Istituzione random","IUS/07",4),
            new TakenExamDTO(6,"Storia test","IUS/03",6),
            new TakenExamDTO(7,"Storia test II","IUS/03",6),
            new TakenExamDTO(8,"Test name 8","IUS/0",9),
        ]); 
       
        $block1 = new ExamBlockDTO(1,2);
        $block2 = new ExamBlockDTO(2,2);
        $block3 = new ExamBlockDTO(3,1);
        $block4 = new ExamBlockDTO(4,1);
        $block5 = new ExamBlockDTO(5,1);
        $this->blocks = [$block1, $block2, $block3, $block4,$block5];
        
        $this->options = collect([
            new ExamOptionDTO(1,"Diritto Privato a distanza", $block1, 12, "IUS/01"),
            new ExamOptionDTO(2,"Istituzione di Diritto ", $block1, 12, "IUS/09"),
            new ExamOptionDTO(3,"Diritto commerciale a distanza", $block2, 12, "IUS/04"),
            new ExamOptionDTO(4,"Diritto di qualcosa", $block2, 9, "IUS/03"),
            new ExamOptionDTO(5,"Istituzione generica", $block3, 6, "IUS/07"),
            new ExamOptionDTO(12,"Storia di qualcosa", $block4, 6, "STO/19"),
            new ExamOptionDTO(13,"Altro esame IUS/09", $block5, 9, "IUS/12"),
        ]);
        
        $this->options[5]->addCompatibleOption("IUS/03");
        $this->options[6]->addCompatibleOption("IUS/04");
    }
    
    private function setupMocks(){
        $this->frontManager->expects($this->any())
                ->method("getTakenExams")->willReturn($this->takenExams);
        
        $this->frontManager->expects($this->any())
                ->method("getExamOptions")->willReturn($this->options);

        $this->frontManager->expects($this->any())
                ->method("getExamBlocks")->willReturn(collect($this->blocks));
    }

    public function test_getOptionsBySsd() {
        $takenExam = new TakenExamDTO(17,"test exam 01","IUS/01",5);
        $option1 = new ExamOptionDTO(1,"test 1", $this->blocks[0], 12, "IUS/01");
        $option2 = new ExamOptionDTO(2,"test 2", $this->blocks[0], 12, "IUS/09");
        $option3 = new ExamOptionDTO(3,"test 3", $this->blocks[1], 12, "IUS/01");
        $option4 = new ExamOptionDTO(4,"test 4", $this->blocks[2], 9, "IUS/01");
        $this->options = collect([$option1,$option2,$option3,$option4]);
        $this->examDistance->expects($this->exactly(3))                
                ->method("calculateDistance")
                ->will($this->onConsecutiveCalls(7,9,3));
        $this->setupMocks();
        
        $this->planBuilder->setFront(1);
        
        $orederedOptions = $this->planBuilder->getOptionsBySsd($takenExam);
        
        $this->assertCount(3, $orederedOptions);
        $this->assertEquals($option4, $orederedOptions->first());
        $this->assertEquals($option3, $orederedOptions->last());
    }
    
    public function test_getOptionsByCompatibility() {
        $takenExam = new TakenExamDTO(17,"test exam 01","IUS/02",5);
        $option1 = new ExamOptionDTO(1,"test 1", $this->blocks[0], 12, "IUS/01");
        $option2 = new ExamOptionDTO(2,"test 2", $this->blocks[0], 12, "IUS/09");
        $option3 = new ExamOptionDTO(3,"test 3", $this->blocks[1], 12, "IUS/07");
        $option4 = new ExamOptionDTO(4,"test 4", $this->blocks[2], 9, "IUS/01");
        $option1->addCompatibleOption("IUS/02");
        $option4->addCompatibleOption("IUS/02");
        $option4->addCompatibleOption("IUS/07");
        $this->options = collect([$option1,$option2,$option3,$option4]);
        $this->examDistance->expects($this->exactly(2))                
                ->method("calculateDistance")
                ->will($this->onConsecutiveCalls(7,5));
        $this->setupMocks();
        
        $this->planBuilder->setFront(1);
        
        $orederedOptions = $this->planBuilder->getOptionsByCompatibility($takenExam);
        
        $this->assertCount(2, $orederedOptions);
        $this->assertEquals($option4, $orederedOptions->first());
        $this->assertEquals($option1, $orederedOptions->last());
    }
    
    public function test_real_learning() {
        $this->setupMocks();
        //$this->examDistance->expects($this->exactly(sizeof($this->options)))
        $this->examDistance->expects($this->any())                
                ->method("calculateDistance")->willReturn(1);
        
        $studyPlan = $this->planBuilder->setFront(1)
                ->getStudyPlan();
        
        $this->assertEquals(3, $studyPlan->getExam(1)->getIntegrationValue());
        $this->assertEquals(6, $studyPlan->getExam(2)->getIntegrationValue());
        $this->assertEquals(7, $studyPlan->getExam(3)->getIntegrationValue());
        
        $this->assertEquals(0,$studyPlan->getExam(4)->getIntegrationValue());
        $this->assertCount(2,$studyPlan->getExam(4)->getTakenExams());
        
        //dd($studyPlan->getExam(12));
        $this->assertCount(1,$studyPlan->getExam(12)->getTakenExams());
        $this->assertEquals(3,$studyPlan->getExam(12)->getIntegrationValue());
        //$test = $this->planBuilder->testAssignBySsd();
        //var_dump($test);
    }
}