<?php

namespace Tests\Unit\Factory;

use App\Factories\Implementations\CourseManagerFactoryImpl;
use App\Services\Implementations\CourseManagerImpl;
use Tests\TestCase;

class CourseManagerFactoryImplTest extends TestCase
{
    private CourseManagerFactoryImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->sut = new CourseManagerFactoryImpl();
    }

    
    public function test_factory_instance(){
        
        $instance = $this->sut->getCourseManager(3);
        
        $this->assertInstanceOf(CourseManagerImpl::class, $instance);
    }
}