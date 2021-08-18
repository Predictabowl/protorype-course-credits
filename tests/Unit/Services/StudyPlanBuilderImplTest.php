<?php

namespace Tests\Unit\Services;

use App\Models\Course;
use App\Domain\ExamOptionDTO;
use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Services\Interfaces\ExamDistance;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\CourseManager;
use PHPUnit\Framework\TestCase;

class StudyPlanBuilderImplTest extends TestCase
{
    private $frontManager;
    private $courseManager;
    private $examDistance;
    private $planBuilder;
    private $takenExams;
    private $blocks;
    private $options;

    protected function setUp(): void
    {
        $this->frontManager = $this->createMock(FrontManager::class);
        $this->courseManager = $this->createMock(CourseManager::class);
        $this->examDistance = $this->createMock(ExamDistance::class);
        $this->setupData();
        
        app()->instance(ExamDistance::class, $this->examDistance);
        $this->planBuilder = new StudyPlanBuilderImpl($this->frontManager, $this->courseManager);
    }
    
    
    private function setupMocks(){
        $this->frontManager->expects($this->any())
                ->method("getTakenExams")
                ->willReturn($this->takenExams);
        
        $this->courseManager->expects($this->any())
                ->method("getExamOptions")
                ->willReturn($this->options);

        $this->courseManager->expects($this->any())
                ->method("getExamBlocks")
                ->willReturn(collect($this->blocks));
    }
    
     public function test_getOptionsBySsd_when_no_ssd_associated() {
        $takenExam = new TakenExamDTO(17,"test exam 01","IUS/01",5);
        $option1 = new ExamOptionDTO(1,"test 1", $this->blocks[0], 12, null);
        $this->options = collect([$option1]);
        $this->examDistance->expects($this->never())
                ->method("calculateDistance");
        $this->setupMocks();
        
        $this->planBuilder->refreshStudyPlan();
        
        $orederedOptions = $this->planBuilder->getOptionsBySsd($takenExam);
        
        $this->assertEmpty($orederedOptions);
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
        
        $this->planBuilder->refreshStudyPlan();
        
        $orederedOptions = $this->planBuilder->getOptionsBySsd($takenExam);
        
        $this->assertCount(3, $orederedOptions);
        $this->assertEquals($option4, $orederedOptions->first());
        $this->assertEquals($option3, $orederedOptions->last());
    }
    
    public function test_getOptionsByCompatibility() {
        $takenExam = new TakenExamDTO(17,"test exam 01","IUS/02",5);
        $option1 = new ExamOptionDTO(1,"test 1", $this->blocks[0], 12, null);
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
        
        $this->planBuilder->refreshStudyPlan();
        
        $orederedOptions = $this->planBuilder->getOptionsByCompatibility($takenExam);
        
        $this->assertCount(2, $orederedOptions);
        $this->assertEquals($option4, $orederedOptions->first());
        $this->assertEquals($option1, $orederedOptions->last());
    }
    
    public function test_getFreeChoiceOptions() {
        $takenExam = new TakenExamDTO(17,"test exam 01","IUS/02",5);
        $option1 = new ExamOptionDTO(1,"test 1", $this->blocks[0], 12, null);
        $option2 = new ExamOptionDTO(2,"test 2", $this->blocks[0], 12, "IUS/09");
        $option3 = new ExamOptionDTO(3,"test 3", $this->blocks[1], 12, null);
        $option4 = new ExamOptionDTO(4,"test 4", $this->blocks[2], 9, "IUS/01");
        $option1->addCompatibleOption("IUS/02");
        $option4->addCompatibleOption("IUS/02");
        $option4->addCompatibleOption("IUS/07");
        $this->options = collect([$option1,$option2,$option3,$option4]);
        $this->examDistance->expects($this->exactly(2))                
                ->method("calculateDistance")
                ->will($this->onConsecutiveCalls(7,5));
        $this->setupMocks();
        
        $this->planBuilder->refreshStudyPlan();
        
        $orederedOptions = $this->planBuilder->getFreeChoiceOptions($takenExam);
        
        $this->assertCount(2, $orederedOptions);
        $this->assertEquals($option3, $orederedOptions->first());
        $this->assertEquals($option1, $orederedOptions->last());
    }
    
    public function test_getStudyPlan_with_free_choice_exam() {
         $this->takenExams = collect([
            new TakenExamDTO(1,"Diritto Privato","IUS/01",9),
        ]); 
         
        $block1 = new ExamBlockDTO(1,1);
        $block2 = new ExamBlockDTO(2,1);
        $block3 = new ExamBlockDTO(3,1);
        $this->blocks = [$block1, $block2, $block3];
        
         $this->options = collect([
            new ExamOptionDTO(1,"Esame a scelta", $block1, 12, null),
            new ExamOptionDTO(2,"Istituzione di Diritto ", $block2, 12, "IUS/09"),
            new ExamOptionDTO(3,"Altro esame", $block3, 6, "IUS/07"),
        ]);        
        $this->options[1]->addCompatibleOption("IUS/03");
        
        $this->setupMocks();
        $this->examDistance->expects($this->once())                
                ->method("calculateDistance")
                ->willReturn(1);
        
        $studyPlan = $this->planBuilder->getStudyPlan();

        $this->assertEquals(3, $studyPlan->getExam(1)->getIntegrationValue());
        $this->assertEquals(12, $studyPlan->getExam(2)->getIntegrationValue());
        $this->assertEquals(6, $studyPlan->getExam(3)->getIntegrationValue());
        
        $this->assertCount(1,$studyPlan->getExam(1)->getTakenExams());
        $this->assertCount(0,$studyPlan->getExam(2)->getTakenExams());
        $this->assertCount(0, $studyPlan->getExam(3)->getTakenExams());
        
    }
    
     public function test_getStudyPlan() {
        
        $this->setupMocks();
        $this->examDistance->expects($this->exactly(12))                
                ->method("calculateDistance")
                ->willReturn(1);
        
        $studyPlan = $this->planBuilder->getStudyPlan();

        $this->assertEquals(3, $studyPlan->getExam(1)->getIntegrationValue());
        $this->assertEquals(6, $studyPlan->getExam(2)->getIntegrationValue());
        $this->assertEquals(7, $studyPlan->getExam(3)->getIntegrationValue());
        
        $this->assertEquals(0,$studyPlan->getExam(4)->getIntegrationValue());
        $this->assertCount(2,$studyPlan->getExam(4)->getTakenExams());
        
        $this->assertCount(1,$studyPlan->getExam(12)->getTakenExams());
        $this->assertEquals(3,$studyPlan->getExam(12)->getIntegrationValue());
        
        $this->assertCount(1,$studyPlan->getExam(13)->getTakenExams());
        $this->assertEquals(0,$studyPlan->getExam(13)->getIntegrationValue());
        
        $this->assertCount(0, $studyPlan->getExam(14)->getTakenExams());
        $this->assertEquals(2,$studyPlan->getExam(14)->getIntegrationValue());
        
    }
   
    
    private function setupData() {
        /* This data is highly skewed as it doesnt adhere to the 
         * pre-conditions, but it was crafted knowing that won't generate
         * inconsistencies and will test few boundary cases.
         * 
         */
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
            new ExamOptionDTO(14,"Altro esame IUS/07", $block3, 2, "IUS/07"),
        ]);
        
        $this->options[5]->addCompatibleOption("IUS/03");
        $this->options[6]->addCompatibleOption("IUS/0");
    }
}