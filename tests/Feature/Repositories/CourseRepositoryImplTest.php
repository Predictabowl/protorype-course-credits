<?php

namespace Tests\Feature\Repositories;


use App\Repositories\Implementations\CourseRepositoryImpl;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class CourseRepositoryImplTest extends TestCase
{
    use RefreshDatabase;
    
    private $repository;
    
    protected function setUp(): void {
        parent::setUp();
        $this->repository = new CourseRepositoryImpl();
    }
    
    public function test_get_when_not_present(){
        $sut = $this->repository->get(2);
        
        $this->assertNull($sut);
    }
    
    public function test_get_success()
    {
        $course = Course::factory()->create();
        
        $found = $this->repository->get(1);
        
        $this->assertEquals(
                [$course->id, $course->name, $course->cfu],
                [$found->id, $found->name, $found->cfu]);
        
    }
    
    public function test_save_success() {
        $attributes = [
            "name" => "test name",
            "cfu" => 180
        ];
        $course = Course::make($attributes);
        
        $result = $this->repository->save($course);
        
        $this->assertTrue($result);
        $this->assertDatabaseCount("courses", 1);
        $this->assertDatabaseHas("courses", $attributes);
    }
    
    public function test_save_with_duplicate_name_should_fail() {
        $attributes = [
            "name" => "test name",
            "cfu" => 180
        ];
        Course::create($attributes);
        $attributes2 = [
            "name" => "test name",
            "cfu" => 120
        ];
        $course = Course::make($attributes2);
        
        $result = $this->repository->save($course);
        
        $this->assertFalse($result);
        $this->assertDatabaseCount("courses", 1);
        $this->assertDatabaseMissing("courses", $attributes2);
    }

    public function test_delete_sucess(){
        $attributes = [
            "name" => "test name 2",
            "cfu" => 170
        ];
        $course = Course::create($attributes);
        
        $result = $this->repository->delete($course->id);
        
        $this->assertTrue($result);
        $this->assertDatabaseCount("courses", 0);
    }
    
    public function test_delete_failure(){
        $result = $this->repository->delete(17);
        
        $this->assertFalse($result);
        $this->assertDatabaseCount("courses", 0);
    }
}
