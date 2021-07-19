<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Services;

use App\Models\TakenExam;
use App\Domain\TakenExamDTO;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Services\Implementations\FrontManagerImpl;
use App\Mappers\Interfaces\TakenExamMapper;
use PHPUnit\Framework\TestCase;

/**
 * Description of FrontManagerImplTest
 *
 * @author piero
 */
class CourseManagerImplTest extends TestCase{

    private const FIXTURE_FRONT_ID = 7;    
    
    private $takenRepo;
    private $manager;
    private $mapper;

    
    protected function setUp():void
    {
        parent::setUp();
        $factory = $this->createMock(RepositoriesFactory::class);
        $this->takenRepo = $this->createMock(TakenExamRepository::class);
        $this->mapper = $this->createMock(TakenExamMapper::class);
        
        $factory->method("getTakenExamRepository")
                ->willReturn($this->takenRepo);      
        
        app()->instance(RepositoriesFactory::class,$factory);
        app()->instance(TakenExamMapper::class, $this->mapper);
        $this->manager = new FrontManagerImpl(self::FIXTURE_FRONT_ID);
    }
  
    
    public function test_getExamBlocks() {
        $blocks = collect([new ExamBlockDTO(1, 2), new ExamBlockDTO(1, 1)]);
        $this->blockRepo->expects($this->once())->method("getFromFront")
                ->willReturn($blocks);
        $this->setManagerInstance();
        
        $sut = $this->manager->getExamBlocks();
        
        $this->assertSame($blocks, $sut);
    }
    
    public function test_getExamBlocks_when_course_not_set() {
        $this->blockRepo->expects($this->once())->method("getFromFront")
                ->willReturn(collect([]));
        $this->setManagerInstance(null);
        
        $sut = $this->manager->getExamBlocks();
        
        $this->assertEmpty($sut);
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
    
}
