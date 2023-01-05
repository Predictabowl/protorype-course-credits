<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\Custom\CourseNotFoundException;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\Ssd;
use App\Repositories\Implementations\CourseRepositoryImpl;
use App\Repositories\Interfaces\ExamBlockRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class CourseRepositoryImplTest extends TestCase {

    use RefreshDatabase;

    private CourseRepositoryImpl $sut;
    private ExamBlockRepository $ebRepo;

    protected function setUp(): void {
        parent::setUp();
        $this->ebRepo = $this->createMock(ExamBlockRepository::class);

        $this->sut = new CourseRepositoryImpl($this->ebRepo);
    }

    public function test_get_when_not_present() {
        $get = $this->sut->get(2);

        $this->assertNull($get);
    }

    public function test_get_success_withLazyLoading() {
        $course = Course::factory()->create();
        $examBlock = ExamBlock::factory()->create();
        $examBlock->course()->associate($course);

        $found = $this->sut->get($course->id);

        $this->assertEquals(
                [$course->id, $course->name, $course->cfu],
                [$found->id, $found->name, $found->cfu]);
        //"relationLoaded" method always return false, I guess is bugged
//        $this->assertFalse($course->relationLoaded("examBlocks"));
        $this->assertEquals(0,sizeof($found->relationsToArray()));
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

    public function test_delete_whenMissing() {
        $result = $this->sut->delete(3);

        $this->assertFalse($result);
    }

    public function test_delete_sucess() {
        $attributes = [
            "name" => "test name 2",
            "cfu" => 170,
            "finalExamCfu" => 7,
            "numberOfYears" => 3,
            "cfuTresholdForYear" => 40
        ];
        Course::create($attributes);
        $course = Course::first();
        ExamBlock::factory(3)->create();
        $examBlocks = ExamBlock::all();
        foreach ($examBlocks as $examBlock) {
            $course->examBlocks()->save($examBlock);
        }

        $this->ebRepo->expects($this->exactly(3))
                ->method("delete")
                ->withConsecutive(
                        [$this->equalTo($examBlocks->get(0)->id)],
                        [$this->equalTo($examBlocks->get(1)->id)],
                        [$this->equalTo($examBlocks->get(2)->id)]
        );

        $result = $this->sut->delete($course->id);

        $this->assertTrue($result);
        $this->assertDatabaseCount("courses", 0);
    }

    public function test_delete_failure() {
        $result = $this->sut->delete(17);

        $this->assertFalse($result);
        $this->assertDatabaseCount("courses", 0);
    }

    public function test_update_whenCourseNotPresent_shouldThrow() {
        $course = Course::factory()->make();

        $this->expectException(CourseNotFoundException::class);
        $this->sut->update($course);
    }

    public function test_update_success() {
        $course = Course::factory()->create([
            "name" => "old name"
        ]);
        $course->name = "new name";

        $result = $this->sut->update($course);

        $this->assertTrue($result);
        $modified = Course::find($course->id);
        $this->assertEquals("new name", $modified->name);
    }

    public function test_getAll_whenEmpty() {
        $courses = $this->sut->getAll();

        $this->assertEmpty($courses);
    }

    public function test_getAll_noFilters_success() {
        Course::factory(2)->create();
        $courses = Course::all();

        $all = $this->sut->getAll();

        $this->assertEquals($courses, $all);
    }

    public function test_getAll_withFilters_success() {
        $course1 = Course::factory()->create(["name" => "test name"]);
        Course::factory()->create(["name" => "normal name"]);
        $course3 = Course::factory()->create(["name" => "another test"]);
        $course1 = Course::find($course1->id);
        $course3 = Course::find($course3->id);

        $all = $this->sut->getAll(["search" => "test"]);

        $this->assertEquals($course1, $all->get(0));
        $this->assertEquals($course3, $all->get(1));
    }

    public function test_getFromName_whenMissing() {
        $course = $this->sut->getFromName("test name");

        $this->assertNull($course);
    }

    public function test_getFromName_success() {
        Course::factory()->create(["name" => "test name"]);
        $course = Course::all()->first();

        $result = $this->sut->getFromName("test name");

        $this->assertEquals($course, $result);
    }

    public function test_get_withFullDepth() {
        $course = Course::factory()->create();
        $examBlock = ExamBlock::factory()->create();
        $examBlock->course()->associate($course);
        Ssd::factory()->create();
        ExamBlockOption::factory()->create([
            "exam_block_id" => $examBlock->id,
            "exam_id" => Exam::factory()->create()
        ]);

        $found = $this->sut->get($course->id, true);

        $this->assertEquals(
                [$course->id, $course->name, $course->cfu],
                [$found->id, $found->name, $found->cfu]);

        $this->assertNotNull($found->examBlocks());
        $relationships = $found->relationsToArray();
        $this->assertEquals(1,sizeof($relationships));
        $this->assertArrayHasKey("exam_blocks",$relationships);
        $this->assertArrayHasKey("exam_block_options",$relationships["exam_blocks"][0]);
        $this->assertArrayHasKey("exam",$relationships["exam_blocks"][0]
                ["exam_block_options"][0]);
    }

}
