<?php

namespace Tests\Unit\Factory;

use App\Factories\Implementations\StudyPlanBuilderFactoryImpl;
use App\Factories\Interfaces\CourseDataBuilderFactory;
use App\Models\Course;
use App\Models\Front;
use App\Services\Implementations\StudyPlanBuilderImpl;
use App\Services\Interfaces\CourseAdminManager;
use App\Services\Interfaces\CourseDataBuilder;
use App\Services\Interfaces\ExamDistance;
use App\Services\Interfaces\FrontManager;
use Tests\TestCase;

class StudyPlanBuilderFactoryImplTest extends TestCase
{
    
    private StudyPlanBuilderFactoryImpl $sut;
    private FrontManager $frontManager;
    private ExamDistance $examDistance;
    private CourseDataBuilderFactory $courseDataBuilderFactory;
    
    protected function setUp(): void {
        parent::setUp();
        $this->frontManager = $this->createMock(FrontManager::class);
        $this->examDistance = $this->createMock(ExamDistance::class);
        $this->courseDataBuilderFactory = $this->createMock(CourseDataBuilderFactory::class);
        
        $this->sut = new StudyPlanBuilderFactoryImpl(
                $this->frontManager,
                $this->courseDataBuilderFactory,
                $this->examDistance);
    }

    
    public function test_factory_instance(){
        $this->frontManager->expects($this->once())
                ->method("getTakenExams")
                ->with(3)
                ->willReturn(collect([]));
        
        $course = new Course();
        $courseDataBuilder = $this->createMock(CourseDataBuilder::class);
        $this->courseDataBuilderFactory->expects($this->once())
                ->method("get")
                ->with($course)
                ->willReturn($courseDataBuilder);
        
        $instance = $this->sut->get(3, $course);
        
        $this->assertInstanceOf(StudyPlanBuilderImpl::class, $instance);
    }
}