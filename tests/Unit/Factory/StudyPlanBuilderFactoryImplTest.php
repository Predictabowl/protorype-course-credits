<?php

namespace Tests\Unit\Factory;

use App\Factories\Implementations\StudyPlanBuilderFactoryImpl;
use App\Factories\Interfaces\CourseManagerFactory;
use App\Factories\Interfaces\FrontManagerFactory;
use App\Services\Interfaces\StudyPlanBuilder;
use Tests\TestCase;

class StudyPlanBuilderFactoryImplTest extends TestCase
{
    private StudyPlanBuilderFactoryImpl $sut;
    private FrontManagerFactory $fmFactory;
    private CourseManagerFactory $cmFactory;
    
    protected function setUp(): void {
        parent::setUp();
        $this->fmFactory = $this->createMock(FrontManagerFactory::class);
        $this->cmFactory = $this->createMock(CourseManagerFactory::class);
        
        $this->sut = new StudyPlanBuilderFactoryImpl($this->fmFactory, $this->cmFactory);
    }

    
    public function test_factory_instance(){
        
        $instance = $this->sut->getStudyPlanBuilder(3,2);
        
        $this->assertInstanceOf(StudyPlanBuilder::class, $instance);
    }
}