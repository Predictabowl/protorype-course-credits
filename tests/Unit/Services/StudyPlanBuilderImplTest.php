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
        $takenExam = new TakenExamDTO(17,"test exam 01","IUS/01",5,28);
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
        $takenExam = new TakenExamDTO(17,"test exam 01","IUS/01",5,23);
        $option1 = new ExamOptionDTO(1,"test 1", $this->blocks[0], "IUS/01");
        $option2 = new ExamOptionDTO(2,"test 2", $this->blocks[0], "IUS/09");
        $option3 = new ExamOptionDTO(3,"test 3", $this->blocks[1], "IUS/01");
        $option4 = new ExamOptionDTO(4,"test 4", $this->blocks[2], "IUS/01");
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
        $takenExam = new TakenExamDTO(17,"test exam 01","IUS/02",5,25);
        $option1 = new ExamOptionDTO(1,"test 1", $this->blocks[0], null);
        $option2 = new ExamOptionDTO(2,"test 2", $this->blocks[0], "IUS/09");
        $option3 = new ExamOptionDTO(3,"test 3", $this->blocks[1], "IUS/07");
        $option4 = new ExamOptionDTO(4,"test 4", $this->blocks[2], "IUS/01");
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
        $takenExam = new TakenExamDTO(17,"test exam 01","IUS/02",5,22);
        $option1 = new ExamOptionDTO(1,"test 1", $this->blocks[0], null);
        $option2 = new ExamOptionDTO(2,"test 2", $this->blocks[0], "IUS/09");
        $option3 = new ExamOptionDTO(3,"test 3", $this->blocks[1], null);
        $option4 = new ExamOptionDTO(4,"test 4", $this->blocks[2], "IUS/01");
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
    
    public function test_getStudyPlan_with_free_choice_exam_with_not_enough_cfu() {
         $this->takenExams = collect([
            new TakenExamDTO(1,"Test low CFU","IUS/01",9,22),
        ]); 
         
        $block1 = new ExamBlockDTO(1,1,12,1);
        $block2 = new ExamBlockDTO(2,1,12,2);
        $block3 = new ExamBlockDTO(3,1,6,3);
        $this->blocks = [$block1, $block2, $block3];
        
         $this->options = collect([
            new ExamOptionDTO(1,"Esame a scelta", $block1, null),
            new ExamOptionDTO(2,"Istituzione di Diritto ", $block2, "IUS/09"),
            new ExamOptionDTO(3,"Altro esame", $block3, "IUS/07"),
        ]);        
        $this->options[1]->addCompatibleOption("IUS/03");
        
        $this->setupMocks();
        // is called once for each pass in the free choices loops
        $this->examDistance->expects($this->exactly(2))                
                ->method("calculateDistance")
                ->willReturn(1);
        
        $studyPlan = $this->planBuilder->getStudyPlan();

        $this->assertEquals(12, $studyPlan->getExam(1)->getIntegrationValue());
        $this->assertEquals(12, $studyPlan->getExam(2)->getIntegrationValue());
        $this->assertEquals(6, $studyPlan->getExam(3)->getIntegrationValue());
        
        $this->assertCount(0,$studyPlan->getExam(1)->getTakenExams());
        $this->assertCount(0,$studyPlan->getExam(2)->getTakenExams());
        $this->assertCount(0, $studyPlan->getExam(3)->getTakenExams());
        
    }
    
        public function test_getStudyPlan_with_free_choice_exam_should_prioritize_whole_exams() {
        $testExam = new TakenExamDTO(2,"Test whole CFU","IUS/01",12,22);
        $this->takenExams = collect([
            new TakenExamDTO(1,"Test fractioned Cfu","IUS/01",18,22,2,12),
            $testExam
        ]); 
         
        $block1 = new ExamBlockDTO(1,1,12,1);
        $block2 = new ExamBlockDTO(2,1,12,2);
        $block3 = new ExamBlockDTO(3,1,6,3);
        $this->blocks = [$block1, $block2, $block3];
        
         $this->options = collect([
            new ExamOptionDTO(1,"Esame a scelta", $block1, null),
            new ExamOptionDTO(2,"Istituzione di Diritto ", $block2, "IUS/09"),
            new ExamOptionDTO(3,"Altro esame", $block3, "IUS/07"),
        ]);        
        $this->options[1]->addCompatibleOption("IUS/03");
        
        $this->setupMocks();
        // On the first pass the fraction CFU is not considered, and so is called only once
        // On the second pass they're both called.
        $this->examDistance->expects($this->exactly(3))                
                ->method("calculateDistance")
                ->willReturn(1);
        
        $studyPlan = $this->planBuilder->getStudyPlan();

        $this->assertEquals(0, $studyPlan->getExam(1)->getIntegrationValue());
        $this->assertEquals(12, $studyPlan->getExam(2)->getIntegrationValue());
        $this->assertEquals(6, $studyPlan->getExam(3)->getIntegrationValue());
        
        $this->assertCount(1,$studyPlan->getExam(1)->getTakenExams());
        $this->assertCount(0,$studyPlan->getExam(2)->getTakenExams());
        $this->assertCount(0, $studyPlan->getExam(3)->getTakenExams());
        
        $this->assertEquals($testExam->getId(), 
                $studyPlan->getExam(1)->getTakenExams()->first()->getId());
        
    }    
    
        public function test_getStudyPlan_with_free_choice_will_take_fractioned_if_have_enough_cfu() {
         $this->takenExams = collect([
            new TakenExamDTO(1,"Test low CFU","IUS/01",15,22,2,12),
        ]); 
         
        $block1 = new ExamBlockDTO(1,1,12,1);
        $block2 = new ExamBlockDTO(2,1,12,2);
        $block3 = new ExamBlockDTO(3,1,6,3);
        $this->blocks = [$block1, $block2, $block3];
        
         $this->options = collect([
            new ExamOptionDTO(1,"Esame a scelta", $block1, null),
            new ExamOptionDTO(2,"Istituzione di Diritto ", $block2, "IUS/09"),
            new ExamOptionDTO(3,"Altro esame", $block3, "IUS/07"),
        ]);        
        $this->options[1]->addCompatibleOption("IUS/03");
        
        $this->setupMocks();
        $this->examDistance->expects($this->once())                
                ->method("calculateDistance")
                ->willReturn(1);
        
        $studyPlan = $this->planBuilder->getStudyPlan();

        $this->assertEquals(0, $studyPlan->getExam(1)->getIntegrationValue());
        $this->assertEquals(12, $studyPlan->getExam(2)->getIntegrationValue());
        $this->assertEquals(6, $studyPlan->getExam(3)->getIntegrationValue());
        
        $this->assertCount(1,$studyPlan->getExam(1)->getTakenExams());
        $this->assertCount(0,$studyPlan->getExam(2)->getTakenExams());
        $this->assertCount(0, $studyPlan->getExam(3)->getTakenExams());
        
    }
    
    public function test_Course_max_cfu_is_set_up(){
        $course = new Course();
        $course->maxRecognizedCfu = 15;
        $this->blocks = collect([]);
        $this->options = collect([]);
        $this->takenExams = collect([]);
        $this->setupMocks();
        
        $this->courseManager->expects($this->any())
                ->method("getCourse")
                ->willReturn($course);
        
        $studyPlan = $this->planBuilder->getStudyPlan();
        
        $this->assertEquals(15, $studyPlan->getMaxCfu());
    }
    
    
    public function test_getStudyPlan() {
        
        $this->setupMocks();
        $this->examDistance->expects($this->exactly(12))                
                ->method("calculateDistance")
                ->willReturn(1);
        
        $studyPlan = $this->planBuilder->getStudyPlan();

        $this->assertEquals(3, $studyPlan->getExam(1)->getIntegrationValue());
        $this->assertEquals(6, $studyPlan->getExam(2)->getIntegrationValue());
        $this->assertEquals(4, $studyPlan->getExam(3)->getIntegrationValue());
        
        $this->assertEquals(0,$studyPlan->getExam(4)->getIntegrationValue());
        $this->assertCount(2,$studyPlan->getExam(4)->getTakenExams());
        
        $this->assertCount(1,$studyPlan->getExam(12)->getTakenExams());
        $this->assertEquals(3,$studyPlan->getExam(12)->getIntegrationValue());
        
        $this->assertCount(1,$studyPlan->getExam(13)->getTakenExams());
        $this->assertEquals(0,$studyPlan->getExam(13)->getIntegrationValue());
        
        $this->assertCount(0, $studyPlan->getExam(14)->getTakenExams());
        $this->assertEquals(6,$studyPlan->getExam(14)->getIntegrationValue());
        
    }
    
    public function test_getStudyPlan_with_max_cfu() {
        $course = new Course();
        $course->maxRecognizedCfu = 15;
        
        $this->courseManager->expects($this->any())
                ->method("getCourse")
                ->willReturn($course);
        
        $this->setupMocks();
        $this->examDistance->expects($this->exactly(12))                
                ->method("calculateDistance")
                ->willReturn(1);
        
        $studyPlan = $this->planBuilder->getStudyPlan();

        $this->assertEquals(15, $studyPlan->getRecognizedCredits());
        $this->assertEquals(0, $studyPlan->getLeftoverAllottedCfu());
        $this->assertCount(6,$studyPlan->getLeftoverExams());
    }
   
    
    private function setupData() {
        $this->takenExams = collect([
            new TakenExamDTO(1,"Diritto Privato","IUS/01",9, 19),
            new TakenExamDTO(2,"Istituzione di diritto","IUS/09",6, 20),
            new TakenExamDTO(3,"Diritto Commerciale mod I","IUS/04",5, 24),
            new TakenExamDTO(4,"Diritto Commerciale mod II","IUS/07",5, 23),
            new TakenExamDTO(5,"Istituzione random","IUS/07",4, 26),
            new TakenExamDTO(6,"Storia test","IUS/03",6, 19),
            new TakenExamDTO(7,"Storia test II","IUS/03",6, 20),
            new TakenExamDTO(8,"Test name 8","IUS/0",9, 21),
        ]); 
       
        $block1 = new ExamBlockDTO(1,2,12,2);
        $block2 = new ExamBlockDTO(2,2,9,3);
        $block3 = new ExamBlockDTO(3,1,6,1);
        $block4 = new ExamBlockDTO(4,1,6,2);
        $block5 = new ExamBlockDTO(5,1,9,3);
        $this->blocks = [$block1, $block2, $block3, $block4,$block5];
        
        $this->options = collect([
            new ExamOptionDTO(1,"Diritto Privato a distanza", $block1, "IUS/01"),
            new ExamOptionDTO(2,"Istituzione di Diritto ", $block1, "IUS/09"),
            new ExamOptionDTO(3,"Diritto commerciale a distanza", $block2, "IUS/04"),
            new ExamOptionDTO(4,"Diritto di qualcosa", $block2, "IUS/03"),
            new ExamOptionDTO(5,"Istituzione generica", $block3, "IUS/07"),
            new ExamOptionDTO(12,"Storia di qualcosa", $block4, "STO/19"),
            new ExamOptionDTO(13,"Altro esame IUS/09", $block5, "IUS/12"),
            new ExamOptionDTO(14,"Altro esame IUS/07", $block3, "IUS/07"),
        ]);
        
        $this->options[5]->addCompatibleOption("IUS/03");
        $this->options[6]->addCompatibleOption("IUS/0");
    }

}