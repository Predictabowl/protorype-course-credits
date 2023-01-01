<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\Custom\CourseNotFoundException;
use App\Models\Course;
use App\Models\Exam;
use App\Repositories\Implementations\CourseRepositoryImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class CourseRepositoryImplTest extends TestCase
{
    use RefreshDatabase;

    private CourseRepositoryImpl $sut;

    protected function setUp(): void {
        parent::setUp();
        $this->sut = new CourseRepositoryImpl();
    }

    public function test_get_when_not_present() {
        $get = $this->sut->get(2);

        $this->assertNull($get);
    }

    public function test_get_success()
    {
        $course = Course::factory()->create();

        $found = $this->sut->get(1);

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

        $result = $this->sut->save($course);

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

        $saved = $this->sut->save($course);

        $this->assertDatabaseCount("courses", 0);
        $this->assertFalse($saved);
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

        $result = $this->sut->save($course);

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

        $result = $this->sut->delete($course->id);

        $this->assertTrue($result);
        $this->assertDatabaseCount("courses", 0);
    }

    public function test_delete_failure(){
        $result = $this->sut->delete(17);

        $this->assertFalse($result);
        $this->assertDatabaseCount("courses", 0);
    }

    public function test_update_whenCourseNotPresent_shouldThrow(){
        $course = Course::factory()->make();

        $this->expectException(CourseNotFoundException::class);
        $this->sut->update($course);
    }

    public function test_update_success(){
        $course = Course::factory()->create([
            "name" => "old name"
        ]);
        $course->name = "new name";

        $result = $this->sut->update($course);

        $this->assertTrue($result);
        $modified = Course::find($course->id);
        $this->assertEquals("new name",$modified->name);
//        $this->repository->update($course);
    }

    public function test_getAll_whenEmpty(){
        $courses = $this->sut->getAll();

        $this->assertEmpty($courses);
    }

    public function test_getAll_noFilters_success(){
        Course::factory(2)->create();
        $courses = Course::all();

        $all = $this->sut->getAll();

        $this->assertEquals($courses, $all);
    }

    public function test_getAll_withFilters_success(){
        $course1 = Course::factory()->create(["name" => "test name"]);
        Course::factory()->create(["name" => "normal name"]);
        $course3 = Course::factory()->create(["name" => "another test"]);
        $course1 = Course::find($course1->id);
        $course3 = Course::find($course3->id);

        $all = $this->sut->getAll(["search" => "test"]);


        $this->assertEquals($course1, $all->get(0));
        $this->assertEquals($course3, $all->get(1));
    }

}
