<?php

namespace Tests\Unit\Services;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Services\Implementations\FrontManagerImpl;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use PHPUnit\Framework\TestCase;

class FrontManagerImplTest extends TestCase
{
    private $front;
    private $factory;
    private $takenRepo;
    private $blockRepo;
    

    protected function setUp():void
    {
        $this->factory = $this->createMock(RepositoriesFactory::class);
        $this->takenRepo = $this->createMock(TakenExamRepository::class);
        $this->blockRepo = $this->createMock(ExamBlockRepository::class);
        
        $this->factory->method("getTakenExamRepository")
                ->willReturn($this->takenRepo);
        $this->factory->method("getExamBlockRepository")
                ->willReturn($this->blockRepo);
        
        $this->front = new FrontManagerImpl($this->factory);
    }
    
    public function test_getExamBlocks() {
        $blocks = collect([new ExamBlockDTO(1, 2)]);
        $this->blockRepo->expects($this->exactly(1))->method("getAll")
                ->willReturn($blocks);
        
        $sut = $this->front->setFront(1)->getExamBlocks();
        
        $this->assertSame($blocks, $sut);
    }
    /*
    public function test_getTakenExams() {
        $exams = $this->front->getTakenExams();
        
        $this->assertEquals(DatabaseSeederTest::FIXTURE_NUM_EXAMS, $exams->count());
        $this->assertContainsOnlyInstancesOf(TakenExamDTO::class, $exams);
        
    }
    
    public function test_getExamOptions() {
        $exams = $this->front->getExamOptions();
        
        //$this->assertEquals(DatabaseSeederTest::FIXTURE_NUM_EXAMS, $exams->count());
        //$this->assertContainsOnlyInstancesOf(TakenExamDTO::class, $exams);
        dd($exams);
        
    }*/


    private function populateDB()
    {
        User::factory()->create();

        $this->course = Course::factory()->create([
            "code" => "code01",
            "name" => "Course Test"
        ]);

        Front::factory()->create([
            "course_id" => 1,
            "user_id" => 1
        ]);
        

        $ssds = Ssd::factory(10)->create();

        $block1 = ExamBlock::factory()->create([
            "cfu" => 12,
            "max_exams" => 2,
            "course_id" => $this->course->id
        ]);

        $block2 = ExamBlock::factory()->create([
            "cfu" => 18,
            "max_exams" => 1,
            "course_id" => $this->course->id
        ]);

        $exam1 = Exam::factory()->create([
            "ssd_id" => 1,
            "name" => "test exam 01",
            "cfu" => 12
        ]);

        $exam2 = Exam::factory()->create([
            "ssd_id" => 2,
            "name" => "test exam 02",
            "cfu" => 12
        ]);

        $exam3 = Exam::factory()->create([
            "ssd_id" => 3,
            "name" => "test exam 03",
            "cfu" => 9
        ]);        

        $exam4 = Exam::factory()->create([
            "ssd_id" => 4,
            "name" => "test exam 04",
            "cfu" => 6
        ]);        


        $option1 = ExamBlockOption::factory()->create([
            "exam_id" => $exam1->id,
            "exam_block_id" => $block1->id
        ]);

        $option2 = ExamBlockOption::factory()->create([
            "exam_id" => $exam2->id,
            "exam_block_id" => $block1->id
        ]);

        $option3 = ExamBlockOption::factory()->create([
            "exam_id" => $exam3->id,
            "exam_block_id" => $block2->id
        ]);


        TakenExam::factory()->create([
            "name" => "test exam 01 mod 1",
            "cfu" => 6,
            "ssd_id" => 1,
            "front_id" => 1
        ]);

        TakenExam::factory()->create([
            "name" => "test exam 02 mod 2",
            "cfu" => 9,
            "ssd_id" => 2,
            "front_id" => 1
        ]);

        TakenExam::factory()->create([
            "name" => "test exam 03 mod 3",
            "cfu" => 5,
            "ssd_id" => 3,
            "front_id" => 1
        ]);
    }
}