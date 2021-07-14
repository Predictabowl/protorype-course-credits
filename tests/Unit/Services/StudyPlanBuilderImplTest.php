<?php

namespace Tests\Unit\Services;

use App\Domain\ExamOptionDTO;
use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Models\Front;
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
            new TakenExamDTO(6,"Storia test","STO/19",7),
        ]); 
       
        $block1 = new ExamBlockDTO(1,2);
        $block2 = new ExamBlockDTO(2,2);
        $block3 = new ExamBlockDTO(3,1);
        $block4 = new ExamBlockDTO(4,1);
        $this->blocks = [$block1, $block2, $block3, $block4];
        
        $this->options = collect([
            new ExamOptionDTO(1,"Diritto Privato a distanza", $block1, 12, "IUS/01"),
            new ExamOptionDTO(2,"Istituzione di Diritto ", $block1, 12, "IUS/09"),
            new ExamOptionDTO(3,"Diritto commerciale a distanza", $block2, 12, "IUS/04"),
            new ExamOptionDTO(4,"Diritto di qualcosa", $block2, 12, "IUS/03"),
            new ExamOptionDTO(5,"Istituzione generica", $block3, 6, "IUS/07"),
        ]);
        
        $this->options[3]->addCompatibleOption(new ExamOptionDTO(12,"Storia di qualcosa", $block4, 6, "STO/19"),);
    }
    
    private function setupMocks(){
        $this->frontManager->expects($this->any())
                ->method("getTakenExams")->willReturn($this->takenExams);
        
        $this->frontManager->expects($this->any())
                ->method("getExamOptions")->willReturn($this->options);

        $this->frontManager->expects($this->any())
                ->method("getExamBlocks")->willReturn(collect($this->blocks));
    }

    public function test_populate_data() {
        $this->setupMocks();
        $this->examDistance->expects($this->any())
                ->method("calculateDistance")->willReturn(1);
        $this->planBuilder->setFront(new Front());
        
        $this->assertCount(sizeof($this->takenExams), $this->planBuilder->getTakenExams());
        //$this->assertEquals($this->takenExams, $this->planBuilder->getTakenExams());
    }
    
    public function test_real_learning() {
        $this->setupMocks();
        //$this->examDistance->expects($this->exactly(sizeof($this->options)))
        $this->examDistance->expects($this->any())                
                ->method("calculateDistance")->willReturn(1);
        
        $studyPlan = $this->planBuilder->setFront(new Front())
                ->getStudyPlan();
        
        $this->assertEquals(3, $studyPlan->getExam(1)->getIntegrationValue());
        $this->assertEquals(6, $studyPlan->getExam(2)->getIntegrationValue());
        $this->assertEquals(7, $studyPlan->getExam(3)->getIntegrationValue());
        
        dd($studyPlan->getExam(4));
        //$test = $this->planBuilder->testAssignBySsd();
        //var_dump($test);
    }
    
//    public function test_collection_learning(){
//        $o = new ExamOptionDTO(4,"Istituzione di Diritto ", $this->blocks[1], 12, "IUS/09");
//        $this->takenExams->forget($this->takenExams->search($o));
//        var_dump($this->takenExams);
//    }
    
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