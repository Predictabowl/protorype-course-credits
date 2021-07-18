<?php

namespace Tests\Unit\Services;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Domain\ExamOptionDTO;
use App\Models\Front;
use App\Services\Implementations\FrontManagerStatic;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\FrontRepository;
use App\Exceptions\Custom\UserNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\TestCase;

class FrontManagerStaticTest extends TestCase
{
    private $factory;
    private $takenRepo;
    private $blockRepo;
    private $frontRepo;
    private $manager;
    

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
        $this->manager = new FrontManagerStatic($this->factory);
    }
    
    
    public function test_setFromUser_when_user_not_present(){
        
        $this->frontRepo->method("getFromUser")->will($this->
                throwException(new ModelNotFoundException("message")));
        
        $this->expectException(UserNotFoundException::class);
        
        $this->manager->setFromUser(1);
    }
    
    public function test_setFromUser_when_front_not_present(){
        $this->frontRepo->method("getFromUser")->willReturn(null);
        
        $sut = $this->manager->setFromUser(1);
        
        $this->assertEquals(0, $sut);
        $this->assertNull($this->manager->getActiveFrontId());
    }
    
    public function test_setFromUser_success(){
        $front = new Front();
        $front["id"] = 13;
        $this->frontRepo->method("getFromUser")->willReturn($front);
        
        $sut = $this->manager->setFromUser(1);
        
        $this->assertEquals(1,$sut);
        $this->assertEquals(13, $this->manager->getActiveFrontId());
    }
    

    public function test_getExamBlocks() {
        $blocks = collect([new ExamBlockDTO(1, 2), new ExamBlockDTO(1, 1)]);
        $this->blockRepo->expects($this->once())->method("getFromFront")
                ->willReturn($blocks);
        
        $sut = $this->manager->getExamBlocks();
        
        $this->assertSame($blocks, $sut);
    }


    public function test_getTakenExams() {
        $exams = collect([new TakenExamDTO(1, "name", "ssd1", 6),
                new TakenExamDTO(2, "name 2", "ssd2", 9)]);
        $this->takenRepo->expects($this->once())->method("getFromFront")
                ->willReturn($exams);
        
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
        
        $exams = $this->manager->getExamOptions();
        
        $this->assertSame([$option1,$option2,$option3],
                [$exams[0],$exams[1],$exams[2]]);
        
    }

    public function test_repos_are_called_only_once() {
        $this->blockRepo->expects($this->once())->method("getFromFront");
        $this->takenRepo->expects($this->once())->method("getFromFront");
        
        $this->manager->getExamBlocks();
        $this->manager->getTakenExams();
        $this->manager->getExamOptions();
        $this->manager->getExamBlocks();
        $this->manager->getTakenExams();
        $this->manager->getExamOptions();
    }
    

    public function test_save_takenExam() {
        $exam = new TakenExamDTO(1, "testname", "IUS/0", 7);
        $this->takenRepo->expects($this->once())->method("save")
                ->with($exam,4);
        $this->takenRepo->expects($this->exactly(2))->method("getFromFront");
        $this->frontRepo->method("get")->willReturn(new Front);
        $this->manager->setFront(4);
        
        $this->manager->getTakenExams();
        $this->manager->saveTakenExam($exam);
        $this->manager->getTakenExams();
        $this->manager->getTakenExams();
    }

    public function test_delete_takenExam() {
        $this->takenRepo->expects($this->once())->method("delete")
                ->with(1);
        $this->takenRepo->expects($this->exactly(2))->method("getFromFront");
        
        $this->manager->getTakenExams();
        $this->manager->deleteTakenExam(1);
        $this->manager->getTakenExams();
        $this->manager->getTakenExams();
    }


    public function test_setFront_when_front_not_present(){
        $this->frontRepo->method("get")->willReturn(null);
                
        $sut = $this->manager->setFront(1);
        
        $this->assertEquals(0, $sut);
        $this->assertNull($this->manager->getActiveFrontId());
        
    }
    
    public function test_setFront_when_front_present(){
        $this->frontRepo->method("get")->willReturn(new Front);

        $sut = $this->manager->setFront(3);
        
        $this->assertEquals(1,$sut);
        $this->assertEquals(3, $this->manager->getActiveFrontId());
    }
    
    public function test_changeCourse_success(){
        $this->frontRepo->expects($this->once())
                ->method("updateCourse")
                ->with(3,2)
                ->willReturn(new Front());
        $this->frontRepo->method("get")->willReturn(new Front());
        
        $this->manager->setFront(3);
        
        $this->assertEquals(1, $this->manager->changeCourse(2));
    }
    
    public function test_changeCourse_failure(){
        $this->frontRepo->expects($this->once())
                ->method("updateCourse")
                ->with(3,2)
                ->willReturn(null);
        $this->frontRepo->method("get")->willReturn(new Front());
        
        $this->manager->setFront(3);
        
        $this->assertEquals(0, $this->manager->changeCourse(2));
    }
    
    public function test_createFront_success(){
        $front = new Front();
        $front["id"] = 7;
        $this->frontRepo->expects($this->once())
                ->method("save")
                ->with(3,4)
                ->willReturn($front);
        
        $sut = $this->manager->createFront(3,4);
        $this->assertEquals(1,$sut);
        $this->assertEquals(7,$this->manager->getActiveFrontId());
    }
    
    public function test_createFront_failure(){
        $this->frontRepo->expects($this->once())
                ->method("save")
                ->with(3,4)
                ->willReturn(null);
        
        $sut = $this->manager->createFront(3,4);
        $this->assertEquals(0,$sut);
        $this->assertNull($this->manager->getActiveFrontId());
    }
}