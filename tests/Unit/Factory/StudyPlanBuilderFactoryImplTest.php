<?php

namespace Tests\Unit\Factory;

use App\Factories\Implementations\StudyPlanBuilderFactoryImpl;
use App\Factories\Interfaces\CourseDataBuilderFactory;
use App\Models\Course;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Services\Interfaces\CourseDataBuilder;
use App\Services\Interfaces\CourseManager;
use App\Services\Interfaces\ExamDistance;
use App\Services\Interfaces\FrontManager;
use Tests\TestCase;
use function collect;

class StudyPlanBuilderFactoryImplTest extends TestCase
{
    
    private StudyPlanBuilderFactoryImpl $sut;
    private FrontManager $frontManager;
    private CourseManager $courseManager;
    private ExamDistance $examDistance;
    private CourseDataBuilderFactory $courseDataBuilderFactory;
    
    protected function setUp(): void {
        parent::setUp();
        $this->frontManager = $this->createMock(FrontManager::class);
        $this->examDistance = $this->createMock(ExamDistance::class);
        $this->courseDataBuilderFactory = $this->createMock(CourseDataBuilderFactory::class);
        $this->courseManager = $this->createMock(CourseManager::class);
        
        $this->sut = new StudyPlanBuilderFactoryImpl(
                $this->frontManager,
                $this->courseManager,
                $this->courseDataBuilderFactory,
                $this->examDistance);
    }

    
    public function test_factory_instance(){
        $this->frontManager->expects($this->once())
                ->method("getTakenExams")
                ->with(3)
                ->willReturn(collect([]));
        
        $course = new Course();
        $course->setRelation("examBlocks",collect([]));
        $this->courseManager->expects($this->once())
                ->method("getCourseFullDepth")
                ->with(7)
                ->willReturn($course);
        
        $courseDataBuilder = $this->createMock(CourseDataBuilder::class);
        $this->courseDataBuilderFactory->expects($this->once())
                ->method("get")
                ->with($course)
                ->willReturn($courseDataBuilder);
        
        $instance = $this->sut->get(3, 7);
        
        $this->assertInstanceOf(StudyPlanBuilderImpl::class, $instance);
    }
}