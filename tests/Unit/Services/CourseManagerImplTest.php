<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Services;

use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;
use App\Models\ExamBlock;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Services\Implementations\CourseManagerImpl;
use App\Mappers\Interfaces\ExamBlockMapper;
use PHPUnit\Framework\TestCase;

/**
 * Description of FrontManagerImplTest
 *
 * @author piero
 */
class CourseManagerImplTest extends TestCase{

    private const FIXTURE_COURSE_ID = 7;    
    
    private $blockRepo;
    private $manager;
    private $mapper;

    
    protected function setUp():void
    {
        parent::setUp();
        $this->blockRepo = $this->createMock(ExamBlockRepository::class);
        $this->mapper = $this->createMock(ExamBlockMapper::class);
        
        app()->instance(ExamBlockRepository::class, $this->blockRepo);
        app()->instance(ExamBlockMapper::class, $this->mapper);
        $this->manager = new CourseManagerImpl(self::FIXTURE_COURSE_ID);
    }
  
    
    public function test_getExamBlocks() {
        $models = collect([
            new ExamBlock(["id" => 1]), 
            new ExamBlock(["id" => 2])]);
        $blocks = collect([new ExamBlockDTO(1, 2), new ExamBlockDTO(1, 1)]);
        $this->blockRepo->expects($this->once())
                ->method("getFromFront")
                ->with(self::FIXTURE_COURSE_ID)
                ->willReturn($models);
        $this->mapper->expects($this->exactly(2))
                ->method("toDTO")
                ->withConsecutive([$models[0]], [$models[1]])
                ->willReturnOnConsecutiveCalls($blocks[0],$blocks[1]);
        
        $sut = $this->manager->getExamBlocks();
        
        $this->assertEquals($blocks, $sut);
    }
    
    public function test_getExamBlocks_when_course_not_set() {
        $this->blockRepo->expects($this->once())->method("getFromFront")
                ->willReturn(collect([]));
        
        $sut = $this->manager->getExamBlocks();
        
        $this->assertEmpty($sut);
    }


    public function test_getExamOptions() {
        $block1 = new ExamBlockDTO(1, 2);
        $block2 = new ExamBlockDTO(1, 1);
        $option1 = new ExamOptionDTO(1, "name 1", $block1, 12, "ssd1");
        $option2 = new ExamOptionDTO(2, "name 2", $block1, 12, "ssd2");
        $option3 = new ExamOptionDTO(3, "name 3", $block2, 12, "ssd3");
        $models = collect([
            new ExamBlock(["name" => "test"]),
            new ExamBlock(["name" => "name"])]);
        
        $this->blockRepo->expects($this->once())
                ->method("getFromFront")
                ->with(self::FIXTURE_COURSE_ID)
                ->willReturn($models);
        $this->mapper->expects($this->exactly(2))
                ->method("toDTO")
                ->withConsecutive([$models[0]], [$models[1]])
                ->willReturnOnConsecutiveCalls($block1,$block2);
        
        $result = $this->manager->getExamOptions();
        
        $this->assertEquals(collect([$option1,$option2,$option3]), $result);
        
    }
    
}
