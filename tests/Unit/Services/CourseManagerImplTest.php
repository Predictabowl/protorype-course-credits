<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Services;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepository;
use App\Domain\ExamBlockStudyPlanDTO;
use App\Domain\ExamStudyPlanDTO;
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
    
    private ExamBlockRepository $blockRepo;
    private CourseManagerImpl $sut;
    private ExamBlockMapper $mapper;
    private CourseRepository $courseRepo;

    
    protected function setUp():void
    {
        parent::setUp();
        $this->blockRepo = $this->createMock(ExamBlockRepository::class);
        $this->mapper = $this->createMock(ExamBlockMapper::class);
        $this->courseRepo = $this->createMock(CourseRepository::class);
        
        $this->sut = new CourseManagerImpl(self::FIXTURE_COURSE_ID, $this->mapper,
                $this->blockRepo, $this->courseRepo);
    }
  
    
    public function test_getExamBlocks() {
        $models = collect([
            new ExamBlock(["id" => 1]), 
            new ExamBlock(["id" => 2])]);
        $blocks = collect([new ExamBlockStudyPlanDTO(1, 2, 9, null), new ExamBlockStudyPlanDTO(1, 1, 6, 3)]);
        $this->blockRepo->expects($this->once())
                ->method("getFilteredByCourse")
                ->with(self::FIXTURE_COURSE_ID)
                ->willReturn($models);
        $this->mapper->expects($this->exactly(2))
                ->method("toDTO")
                ->withConsecutive([$models[0]], [$models[1]])
                ->willReturnOnConsecutiveCalls($blocks[0],$blocks[1]);
        
        $sut = $this->sut->getExamBlocks();
        
        $this->assertEquals($blocks, $sut);
    }
    
    public function test_getExamBlocks_when_course_not_set() {
        $this->blockRepo->expects($this->once())
                ->method("getFilteredByCourse")
                ->willReturn(collect([]));
        
        $sut = $this->sut->getExamBlocks();
        
        $this->assertEmpty($sut);
    }


    public function test_getExamOptions() {
        $block1 = new ExamBlockStudyPlanDTO(1, 2, 12, 2);
        $block2 = new ExamBlockStudyPlanDTO(1, 1, 12, null);
        $option1 = new ExamStudyPlanDTO(1, "name 1", $block1, "ssd1");
        $option2 = new ExamStudyPlanDTO(2, "name 2", $block1, "ssd2");
        $option3 = new ExamStudyPlanDTO(3, "name 3", $block2, "ssd3");
        $models = collect([
            new ExamBlock(["name" => "test"]),
            new ExamBlock(["name" => "name"])]);
        
        $this->blockRepo->expects($this->once())
                ->method("getFilteredByCourse")
                ->with(self::FIXTURE_COURSE_ID)
                ->willReturn($models);
        $this->mapper->expects($this->exactly(2))
                ->method("toDTO")
                ->withConsecutive([$models[0]], [$models[1]])
                ->willReturnOnConsecutiveCalls($block1,$block2);
        
        $result = $this->sut->getExamOptions();
        
        $this->assertEquals(collect([$option1,$option2,$option3]), $result);
        
    }
    
    public function test_getCourse(){
        $course = new Course();
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with(self::FIXTURE_COURSE_ID)
                ->willReturn($course);
        
        $result = $this->sut->getCourse();
        
        $this->assertSame($course, $result);
    }
    
}
