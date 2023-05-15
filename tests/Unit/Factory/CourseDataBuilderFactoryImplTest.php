<?php

namespace Tests\Unit\Factory;

use App\Factories\Implementations\CourseDatabuilderFactoryImpl;
use App\Mappers\Interfaces\ExamBlockMapper;
use App\Models\Course;
use App\Services\Implementations\CourseDataBuilderImpl;
use PHPUnit\Framework\TestCase;


class CourseDataBuilderFactoryImplTest extends TestCase
{
    
    private ExamBlockMapper $ebMapper;
    private CourseDatabuilderFactoryImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->ebMapper = $this->createMock(ExamBlockMapper::class);
        
        $this->sut = new CourseDatabuilderFactoryImpl($this->ebMapper);
    }

    
    public function test_factory_instance(){
        
        $course = new Course(["id" => 7]);
        $course->setRelation("examBlocks",collect([]));
        
        $instance = $this->sut->get($course);
        
        $this->assertInstanceOf(CourseDataBuilderImpl::class, $instance);
    }
}