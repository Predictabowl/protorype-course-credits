<?php

namespace Tests\Unit\Factory;

use App\Factories\Implementations\CourseManagerFactoryImpl;
use App\Mappers\Interfaces\ExamBlockMapper;
use App\Services\Implementations\CourseManagerImpl;
use App\Services\Interfaces\CourseAdminManager;
use Tests\TestCase;

class CourseManagerFactoryImplTest extends TestCase
{
    private CourseManagerFactoryImpl $sut;
    private CourseAdminManager $courseAdminManager;
    private ExamBlockMapper $examBlockMapper;
    
    protected function setUp(): void {
        parent::setUp();
        $this->courseAdminManager = $this->createMock(CourseAdminManager::class);
        $this->examBlockMapper = $this->createMock(ExamBlockMapper::class);
        
        $this->sut = new CourseManagerFactoryImpl($this->examBlockMapper,
                $this->courseAdminManager);
    }

    
    public function test_factory_instance(){
        
        $instance = $this->sut->getCourseManager(3);
        
        $this->assertInstanceOf(CourseManagerImpl::class, $instance);
    }
}