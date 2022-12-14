<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\Custom\CourseNotFoundException;
use App\Models\Course;
use App\Repositories\Implementations\CourseRepositoryImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
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
            "cfu" => 180,
            "finalExamCfu" => 12,
            "otherActivitiesCfu" => 6,
            "maxRecognizedCfu" => 120,
            "numberOfYears" => 3,
            "cfuTresholdForYear" => 40
        ];
        $course = Course::make($attributes);
        
        $result = $this->repository->save($course);
        
        $this->assertTrue($result);
        $this->assertDatabaseCount("courses", 1);
        $this->assertDatabaseHas("courses", $attributes);
    }
    
     public function test_save_with_id_already_set_should_throw() {
        $attributes = [
            "name" => "test name",
            "cfu" => 180,
            "finalExamCfu" => 12,
            "otherActivitiesCfu" => 6,
            "maxRecognizedCfu" => 120,
            "numberOfYears" => 3,
            "cfuTresholdForYear" => 40,
            "id" => 2
        ];
        $course = Course::make($attributes);
        
        $this->expectException(InvalidArgumentException::class);
        
        $this->repository->save($course);
        
        $this->assertDatabaseCount("courses", 0);
    }
    
    public function test_save_with_duplicate_name_should_fail() {
        $attributes = [
            "name" => "test name",
            "cfu" => 180,
            "finalExamCfu" => 9,
            "numberOfYears" => 3,
            "cfuTresholdForYear" => 40
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
            "cfu" => 170,
            "finalExamCfu" => 7,
            "numberOfYears" => 3,
            "cfuTresholdForYear" => 40
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
    
    public function test_update_whenCourseNotPresent_shouldThrow(){
        $course = Course::factory()->make();
        
        $this->expectException(CourseNotFoundException::class);
        $this->repository->update($course);
    }
    
    public function test_update_success(){
        $course = Course::factory()->create([
            "name" => "old name"
        ]);
        $course->name = "new name";
        
        $bResult = $this->repository->update($course);
  
        $this->assertTrue($bResult);
        $modified = Course::find($course->id);
        $this->assertEquals("new name",$modified->name);
//        $this->repository->update($course);
    }
}
