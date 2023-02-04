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
use App\Services\Implementations\CourseManagerImpl;
use App\Services\Interfaces\CourseAdminManager;
use PHPUnit\Framework\TestCase;
use function collect;

/**
 * Description of FrontManagerImplTest
 *
 * @author piero
 */
class CourseManagerImplTest extends TestCase{

    private const FIXTURE_COURSE_ID = 7;
    
    private CourseManagerImpl $sut;
    private ExamBlockMapper $mapper;
    private CourseAdminManager $courseAdminManager;

    
    protected function setUp():void
    {
        parent::setUp();
        $this->mapper = $this->createMock(ExamBlockMapper::class);
        $this->courseAdminManager = $this->createMock(CourseAdminManager::class);
        
        $this->sut = new CourseManagerImpl(self::FIXTURE_COURSE_ID, $this->mapper,
                $this->courseAdminManager);
    }
  
    
    public function test_getExamBlocks() {
        $models = collect([
            new ExamBlock(["id" => 1]), 
            new ExamBlock(["id" => 2])]);
        $blocks = collect([new ExamBlockStudyPlanDTO(1, 2, 9, null), new ExamBlockStudyPlanDTO(1, 1, 6, 3)]);
        $course = new Course();
        $course->setRelation("examBlocks", $models);
        $this->courseAdminManager->expects($this->once())
                ->method("getCourseFullData")
                ->with(self::FIXTURE_COURSE_ID)
                ->willReturn($course);
        
        $this->mapper->expects($this->exactly(2))
                ->method("toDTO")
                ->withConsecutive([$models[0]], [$models[1]])
                ->willReturnOnConsecutiveCalls(
                        $blocks[0],$blocks[1]);
        
        $result = $this->sut->getExamBlocks(false);
        
        $this->assertEquals($blocks, $result);
        
        $this->sut->getExamBlocks(true);
    }
    
    public function test_getExamBlocks_when_course_not_set() {
        $course = new Course();
        $course->setRelation("examBlocks", collect([]));
        $this->courseAdminManager->expects($this->once())
                ->method("getCourseFullData")
                ->with(self::FIXTURE_COURSE_ID)
                ->willReturn($course);
                
        
        $result = $this->sut->getExamBlocks();
        
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
        
        $course = new Course();
        $course->setRelation("examBlocks", $models);
        $this->courseAdminManager->expects($this->once())
                ->method("getcourseFullData")
                ->with(self::FIXTURE_COURSE_ID)
                ->willReturn($course);
        
        $this->mapper->expects($this->exactly(2))
                ->method("toDTO")
                ->withConsecutive([$models[0]], [$models[1]])
                ->willReturnOnConsecutiveCalls($block1, $block2);
        
        $result = $this->sut->getExamOptions(false);
        
        $this->assertEquals(collect([$option1,$option2,$option3]), $result);
        
        $this->sut->getExamOptions(true);
        
    }
    
    public function test_getCourse(){
        $course = new Course();
        $this->courseAdminManager->expects($this->exactly(2))
                ->method("getCourseFullData")
                ->with(self::FIXTURE_COURSE_ID)
                ->willReturn($course);
        
        $result = $this->sut->getCourse(false);
        $this->assertSame($course, $result);
        
        $result2 = $this->sut->getCourse(false);
        $this->assertSame($course, $result2);
        
        $result3 = $this->sut->getCourse(true);
        $this->assertSame($course, $result3);
    }
    
}
