<?php

namespace Tests\Unit\Services;

use App\Domain\ExamOptionDTO;
use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Models\Front;
use App\Factories\Interfaces\ExamDistanceFactory;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Services\Interfaces\ExamDistance;
use App\Services\Interfaces\FrontManager;
use PHPUnit\Framework\TestCase;

class StudyPlanBuilderImplTest extends TestCase
{
    private $frontManager;
    private $edFactory;
    private $examDistance;
    private $planBuilder;
    private $takenExams;
    private $block;
    private $options;

    protected function setUp(): void
    {
        $this->frontManager = $this->createMock(FrontManager::class);
        $this->edFactory = $this->createMock(ExamDistanceFactory::class);
        $this->examDistance = $this->createMock(ExamDistance::class);
//        $this->edFactory->expects($this->any())
//                ->method("getInstance")->willReturn($this->examDistance);
        
        $this->mocksSetup();
        
//        $this->planBuilder = new StudyPlanBuilderImpl($this->frontManager, $this->edFactory);
        $this->planBuilder = new StudyPlanBuilderImpl($this->frontManager, $this->examDistance);
    }
    
    private function mocksSetup() {
        $this->takenExams = collect([
            new TakenExamDTO("taken 1","ssd1",9),
            new TakenExamDTO("taken 2","ssd2",5),
        ]); 
        
        $this->frontManager->expects($this->any())
                ->method("getTakenExams")->willReturn($this->takenExams);
        
        $this->block = new ExamBlockDTO(2);
        
        $this->options = collect([
            new ExamOptionDTO("exam part 1", $this->block, 12, "ssd1"),
            new ExamOptionDTO("exam 2", $this->block, 12, "ssd2"),
        ]);
        
        $this->options->each(fn($option) => $this->block->addOption($option));
        
        $this->frontManager->expects($this->any())
                ->method("getExamOptions")->willReturn($this->options);
        
        $this->frontManager->expects($this->any())
                ->method("getExamBlocks")->willReturn(collect($this->block));
        
    }

    public function test_populate_data() {
        $this->examDistance->expects($this->any())
                ->method("calculateDistance")->willReturn(1);
        $this->planBuilder->setFront(new Front());
        
        $this->assertEquals($this->takenExams, $this->planBuilder->getTakenExams());
    }
    
    public function test_real_learning() {
        $this->planBuilder = new StudyPlanBuilderImpl($this->frontManager, new \App\Services\Implementations\ExamDistanceByName());
        $this->planBuilder->setFront(new Front());
        $test = $this->planBuilder->testAssignBySsd();
        var_dump($test);
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