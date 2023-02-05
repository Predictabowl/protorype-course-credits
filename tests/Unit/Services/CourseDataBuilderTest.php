<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Services;

use App\Domain\ExamBlockStudyPlanDTO;
use App\Domain\ExamStudyPlanDTO;
use App\Mappers\Interfaces\ExamBlockMapper;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Services\Implementations\CourseDataBuilderImpl;
use PHPUnit\Framework\TestCase;
use function collect;

/**
 * Description of FrontManagerImplTest
 *
 * @author piero
 */
class CourseDataBuilderTest extends TestCase{

    private const FIXTURE_COURSE_ID = 7;
    
    private ExamBlockMapper $mapper;
    private Course $course;

    
    protected function setUp():void
    {
        parent::setUp();
        $this->course = new Course(["id" => self::FIXTURE_COURSE_ID]);
        $this->mapper = $this->createMock(ExamBlockMapper::class);
        
    }
  
    
    public function test_getExamBlocks() {
        $models = collect([
            new ExamBlock(["id" => 1]), 
            new ExamBlock(["id" => 2])]);
        $blocks = collect([new ExamBlockStudyPlanDTO(1, 2, 9, null),
            new ExamBlockStudyPlanDTO(1, 1, 6, 3)]);
        $this->course->setRelation("examBlocks", $models);
        
        $this->mapper->expects($this->exactly(2))
                ->method("toDTO")
                ->withConsecutive([$models[0]], [$models[1]])
                ->willReturnOnConsecutiveCalls(
                        $blocks[0],$blocks[1]);
        
        $sut = new CourseDataBuilderImpl($this->course, $this->mapper);
        $result = $sut->getExamBlocks(false);
        
        $this->assertEquals($blocks, $result);
    }
    
    public function test_getExamBlocks_when_course_not_set() {
        $this->course->setRelation("examBlocks", collect([]));
        
        $sut = new CourseDataBuilderImpl($this->course, $this->mapper);
        $result = $sut->getExamBlocks();
        
        $this->assertEmpty($result);
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
        
        $this->course->setRelation("examBlocks", $models);
        
        $this->mapper->expects($this->exactly(2))
                ->method("toDTO")
                ->withConsecutive([$models[0]], [$models[1]])
                ->willReturnOnConsecutiveCalls($block1, $block2);
        
        $sut = new CourseDataBuilderImpl($this->course, $this->mapper);
        $result = $sut->getExamOptions();
        
        $this->assertEquals(collect([$option1,$option2,$option3]), $result);
    }
    
    public function test_getCourse(){
        $this->course->setRelation("examBlocks", collect([]));
        
        $sut = new CourseDataBuilderImpl($this->course, $this->mapper);
        $result = $sut->getCourse();
        
        $this->assertSame($this->course, $result);
    }
    
}
