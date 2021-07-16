<?php

namespace Tests\Unit\Services;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Domain\ExamOptionDTO;
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
        
        $this->front = new FrontManagerImpl($this->factory,1);
    }
    
    public function test_getExamBlocks() {
        $blocks = collect([new ExamBlockDTO(1, 2), new ExamBlockDTO(1, 1)]);
        $this->blockRepo->expects($this->once())->method("getFromFront")
                ->willReturn($blocks);
        
        $sut = $this->front->getExamBlocks();
        
        $this->assertSame($blocks, $sut);
    }
    
    public function test_getTakenExams() {
        $exams = collect([new TakenExamDTO(1, "name", "ssd1", 6),
                new TakenExamDTO(2, "name 2", "ssd2", 9)]);
        $this->takenRepo->expects($this->once())->method("getFromFront")
                ->willReturn($exams);
        
        $sut = $this->front->getTakenExams();
        
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
        
        $exams = $this->front->getExamOptions();
        
        $this->assertSame([$option1,$option2,$option3],
                [$exams[0],$exams[1],$exams[2]]);
        
    }
    
    public function test_repos_are_called_everytime() {
        $this->blockRepo->expects($this->exactly(4))->method("getFromFront");
        $this->takenRepo->expects($this->exactly(2))->method("getFromFront");
        
        $this->front->getExamBlocks();
        $this->front->getTakenExams();
        $this->front->getExamOptions();
        $this->front->getExamBlocks();
        $this->front->getTakenExams();
        $this->front->getExamOptions();
    }

}