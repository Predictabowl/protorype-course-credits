<?php

namespace Tests\Unit\Services;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Domain\ExamOptionDTO;
use App\Models\Front;
use App\Services\Implementations\FrontInfoManagerImpl;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\FrontRepository;
use App\Exceptions\Custom\FrontNotFoundException;
use PHPUnit\Framework\TestCase;

class FrontInfoManagerImplTest extends TestCase
{
    private const FIXTURE_COURSE_ID = 5;
    private const FIXTURE_FRONT_ID = 7;
    
    private $factory;
    private $takenRepo;
    private $blockRepo;
    private $frontRepo;
    private $manager;
    private $front;
    

    protected function setUp():void
    {
        $this->factory = $this->createMock(RepositoriesFactory::class);
        $this->takenRepo = $this->createMock(TakenExamRepository::class);
        $this->blockRepo = $this->createMock(ExamBlockRepository::class);
        $this->frontRepo = $this->createMock(FrontRepository::class);
        
        $this->factory->method("getTakenExamRepository")
                ->willReturn($this->takenRepo);
        $this->factory->method("getExamBlockRepository")
                ->willReturn($this->blockRepo);
        $this->factory->method("getFrontRepository")
                ->willReturn($this->frontRepo);
    }
    
    private function setManagerInstance(){
        $this->front = new Front(["course_id" => self::FIXTURE_COURSE_ID]);
        $this->front->id = self::FIXTURE_FRONT_ID;
        
        $this->frontRepo->method("get")->willReturn($this->front);
        
        $this->manager = new FrontInfoManagerImpl($this->factory, self::FIXTURE_FRONT_ID);
    }
    
    public function test_Construct_will_fail_if_front_is_not_found(){
        
//        $this->frontRepo->method("get")->will($this->
//                throwException(new ModelNotFoundException("message")));
        $this->frontRepo->method("get")->willReturn(null);
        
        $this->expectException(FrontNotFoundException::class);
        
        new FrontInfoManagerImpl($this->factory,1);
    }
    

    public function test_getExamBlocks() {
        $blocks = collect([new ExamBlockDTO(1, 2), new ExamBlockDTO(1, 1)]);
        $this->blockRepo->expects($this->once())->method("getFromFront")
                ->willReturn($blocks);
        $this->setManagerInstance();
        
        $sut = $this->manager->getExamBlocks();
        
        $this->assertSame($blocks, $sut);
    }


    public function test_getTakenExams() {
        $exams = collect([new TakenExamDTO(1, "name", "ssd1", 6),
                new TakenExamDTO(2, "name 2", "ssd2", 9)]);
        $this->takenRepo->expects($this->once())->method("getFromFront")
                ->willReturn($exams);
        $this->setManagerInstance();
        
        $sut = $this->manager->getTakenExams();
        
        $this->assertSame($exams, $sut);
        
    }

    public function test_getExamOptions() {
        $block1 = new ExamBlockDTO(1, 2);
        $block2 = new ExamBlockDTO(1, 1);
        $option1 = new ExamOptionDTO(1, "name 1", $block1, 12, "ssd1");
        $option2 = new ExamOptionDTO(2, "name 2", $block1, 12, "ssd2");
        $option3 = new ExamOptionDTO(3, "name 3", $block2, 12, "ssd3");
        $this->blockRepo->expects($this->once())->method("getFromFront")
                ->willReturn(collect([$block1,$block2]));
        $this->setManagerInstance();
        
        $exams = $this->manager->getExamOptions();
        
        $this->assertSame([$option1,$option2,$option3],
                [$exams[0],$exams[1],$exams[2]]);
        
    }

    public function test_save_takenExam() {
        $exam = new TakenExamDTO(1, "testname", "IUS/0", 7);
        $this->takenRepo->expects($this->once())->method("save")
                ->with($exam,self::FIXTURE_FRONT_ID);
        
        $this->setManagerInstance();
        
        $this->manager->saveTakenExam($exam);
    }

    public function test_delete_takenExam() {
        $this->takenRepo->expects($this->once())->method("delete")
                ->with(1);
        $this->setManagerInstance();
        
        $this->manager->deleteTakenExam(1);
    }

    
    public function test_setCourse_success(){
        $this->frontRepo->expects($this->once())
                ->method("updateCourse")
                ->with(self::FIXTURE_FRONT_ID,self::FIXTURE_COURSE_ID+2)
                ->willReturn(new Front());
        $this->setManagerInstance();
        
        $result = $this->manager->setCourse(self::FIXTURE_COURSE_ID+2);
        
        
        $this->assertEquals(1, $result);
    }
    
    public function test_setCourse_failure(){
        $this->frontRepo->expects($this->once())
                ->method("updateCourse")
                ->with(self::FIXTURE_FRONT_ID,self::FIXTURE_COURSE_ID+1)
                ->willReturn(null);
        $this->setManagerInstance();
        
        $result = $this->manager->setCourse(self::FIXTURE_COURSE_ID+1);
        
        $this->assertEquals(0, $result);
    }

}