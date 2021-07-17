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
use App\Exceptions\Custom\FrontNotFoundException;
use App\Exceptions\Custom\UserNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\TestCase;

class FrontManagerStaticTest extends TestCase
{
    private $factory;
    private $takenRepo;
    private $blockRepo;
    private $frontRepo;
    

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
    
    
    public function test_setFromUser_when_user_not_present(){
        $manager = new FrontManagerStatic($this->factory);
        $this->frontRepo->method("getFromUser")->will($this->
                throwException(new ModelNotFoundException("message")));
        
        $this->expectException(UserNotFoundException::class);
        
        $manager->setFromUser(1);
        
    }
    
    public function test_setFromUser_when_front_not_present(){
        $manager = new FrontManagerStatic($this->factory);
        $this->frontRepo->method("getFromUser")->willReturn(null);
        
        $sut = $manager->setFromUser(1);
        
        $this->assertEquals(0, $sut);
    }
    
    public function test_setFromUser_success(){
        $manager = new FrontManagerStatic($this->factory);
        $this->frontRepo->method("getFromUser")->willReturn(new Front());
        
        $sut = $manager->setFromUser(1);
        
        $this->assertEquals(1,$sut);
    }
    

    public function test_getExamBlocks() {
        $blocks = collect([new ExamBlockDTO(1, 2), new ExamBlockDTO(1, 1)]);
        $this->blockRepo->expects($this->once())->method("getFromFront")
                ->willReturn($blocks);
        $manager = new FrontManagerStatic($this->factory);
        
        $sut = $manager->getExamBlocks();
        
        $this->assertSame($blocks, $sut);
    }


    public function test_getTakenExams() {
        $exams = collect([new TakenExamDTO(1, "name", "ssd1", 6),
                new TakenExamDTO(2, "name 2", "ssd2", 9)]);
        $this->takenRepo->expects($this->once())->method("getFromFront")
                ->willReturn($exams);
        $manager = new FrontManagerStatic($this->factory);
        
        $sut = $manager->getTakenExams();
        
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
        $manager = new FrontManagerStatic($this->factory);
        
        $exams = $manager->getExamOptions();
        
        $this->assertSame([$option1,$option2,$option3],
                [$exams[0],$exams[1],$exams[2]]);
        
    }

    public function test_repos_are_called_only_once() {
        $manager = new FrontManagerStatic($this->factory);
        $this->blockRepo->expects($this->once())->method("getFromFront");
        $this->takenRepo->expects($this->once())->method("getFromFront");
        
        $manager->getExamBlocks();
        $manager->getTakenExams();
        $manager->getExamOptions();
        $manager->getExamBlocks();
        $manager->getTakenExams();
        $manager->getExamOptions();
    }
    

    public function test_save_takenExam() {
        $exam = new TakenExamDTO(1, "testname", "IUS/0", 7);
        $this->takenRepo->expects($this->once())->method("save")
                ->with($exam,4);
        $this->takenRepo->expects($this->exactly(2))->method("getFromFront");
        $this->frontRepo->method("get")->willReturn(new Front);
        $manager = new FrontManagerStatic($this->factory);
        $manager->setFront(4);
        
        $manager->getTakenExams();
        $manager->saveTakenExam($exam);
        $manager->getTakenExams();
        $manager->getTakenExams();
    }

    public function test_delete_takenExam() {
        $this->takenRepo->expects($this->once())->method("delete")
                ->with(1);
        $this->takenRepo->expects($this->exactly(2))->method("getFromFront");
        $manager = new FrontManagerStatic($this->factory);
        
        $manager->getTakenExams();
        $manager->deleteTakenExam(1);
        $manager->getTakenExams();
        $manager->getTakenExams();
    }


    public function test_setFront_when_front_not_present(){
        $manager = new FrontManagerStatic($this->factory);
        $this->frontRepo->method("get")->willReturn(null);
        $this->expectException(FrontNotFoundException::class);
                
        $sut = $manager->setFront(1);
        
    }
    
    public function test_setFront_when_front_present(){
        $manager = new FrontManagerStatic($this->factory);
        $this->frontRepo->method("get")->willReturn(new Front);

        $sut = $manager->setFront(1);
        
        $this->assertSame($manager,$sut);
    }
}