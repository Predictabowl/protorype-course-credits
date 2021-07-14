<?php

namespace Tests\Unit\Services;

use App\Domain\ExamOptionDTO;
use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Models\Front;
//use App\Factories\Interfaces\ExamDistanceFactory;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Services\Interfaces\ExamDistance;
use App\Services\Interfaces\FrontManager;
use PHPUnit\Framework\TestCase;

class StudyPlanBuilderImplTest extends TestCase
{
    private $frontManager;
    //private $edFactory;
    private $examDistance;
    private $planBuilder;
    private $takenExams;
    private $blocks;
    private $options;

    protected function setUp(): void
    {
        $this->frontManager = $this->createMock(FrontManager::class);
        //$this->edFactory = $this->createMock(ExamDistanceFactory::class);
        $this->examDistance = $this->createMock(ExamDistance::class);
//        $this->edFactory->expects($this->any())
//                ->method("getInstance")->willReturn($this->examDistance);
        
        $this->mocksSetup();
        
//        $this->planBuilder = new StudyPlanBuilderImpl($this->frontManager, $this->edFactory);
        $this->planBuilder = new StudyPlanBuilderImpl($this->frontManager, $this->examDistance);
    }
    
    private function mocksSetup() {
        $this->takenExams = collect([
            new TakenExamDTO(1,"Diritto Privato","IUS/01",9),
            new TakenExamDTO(2,"Istituzione di diritto","IUS/09",5),
            new TakenExamDTO(3,"Diritto Commerciale mod I","IUS/04",6),
        ]); 
        
        $this->frontManager->expects($this->any())
                ->method("getTakenExams")->willReturn($this->takenExams);
        
        $block1 = new ExamBlockDTO(1,2);
        $block2 = new ExamBlockDTO(2,2);
        $this->blocks = [$block1, $block2];
        
        $this->options = collect([
            new ExamOptionDTO(1,"Diritto Privato a distanza", $block1, 12, "IUS/01"),
            new ExamOptionDTO(2,"Istituzione di Diritto ", $block1, 12, "IUS/09"),
            new ExamOptionDTO(3,"Diritto commerciale a distanza", $block1, 12, "IUS/04"),
        ]);
        
        $block1->addOption($this->options[0]);
        $block1->addOption($this->options[1]);
        $block2->addOption($this->options[2]);
        
        $this->frontManager->expects($this->any())
                ->method("getExamOptions")->willReturn($this->options);


        $this->frontManager->expects($this->any())
                ->method("getExamBlocks")->willReturn(collect($this->blocks));
        
    }

    public function test_populate_data() {
        $this->examDistance->expects($this->any())
                ->method("calculateDistance")->willReturn(1);
        $this->planBuilder->setFront(new Front());
        
        $this->assertEquals($this->takenExams, $this->planBuilder->getTakenExams());
    }
    
    public function test_real_learning() {
        $this->planBuilder = new StudyPlanBuilderImpl($this->frontManager, new \App\Services\Implementations\ExamDistanceByName());
        $studyPlan = $this->planBuilder->setFront(new Front())
                ->getStudyPlan();
        
        dd($studyPlan);
        $this->assertEquals(3, $studyPlan->getExam(1)->getIntegrationValue());
        $this->assertEquals(7, $studyPlan->getExam(2)->getIntegrationValue());
        $this->assertEquals(6, $studyPlan->getExam(3)->getIntegrationValue());
        
        $this->assertEquals(16, collect($studyPlan->getExams())->map(
                fn($e) => $e->getIntegrationValue())->sum());
        //$test = $this->planBuilder->testAssignBySsd();
        //var_dump($test);
    }
    
    public function test_collection_learning(){
        $o = new ExamOptionDTO(4,"Istituzione di Diritto ", $this->blocks[1], 12, "IUS/09");
        $this->takenExams->forget($this->takenExams->search($o));
        var_dump($this->takenExams);
    }
    
    // public function test_auto_assigned_distributed_cfu_when_not_set()
    // {
    //     $exams = collect([new ApprovedExam("exam a","ssd1",12),
    //         new ApprovedExam("exam b","ssd1",9),
    //         new ApprovedExam("exam c","ssd1",12),
    //         new ApprovedExam("exam something","ssd1",6)]);

    //     $declaredExam = new DeclaredExam("exam b m1","ssd1",9);

    //     $factory = new ExamDistanceFactoryImpl();

    //     $oredered = $exams->map(fn (ApprovedExam $exam) =>
    //             $factory->getInstance($exam,$declaredExam))
    //         ->sort(fn ($a, $b) => ($a->getDistance() < $b->getDistance())? -1 : 1)
    //         ->map(fn ($item) => $item->getExam1());

    //     dd($oredered);
    // }

//    public function test_learning()
//    {
//         $this->frontManager->expect($this->once())
//             ->method("getExamBlock")
//             ->will($this->mockExamBlock());
//    }


//    private function mockExamBlock()
//    {
//        $block = new ExamBlock();
//        $block->setRawAttributes([
//            "max_exams" => 2,
//            "course_id" => 1
//        ]);
//        return $block;
//    }


}