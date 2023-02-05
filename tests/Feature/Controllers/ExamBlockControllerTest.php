<?php

namespace Tests\Feature\Controllers;

use App\Domain\NewExamBlockInfo;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Models\Role;
use App\Models\User;
use App\Services\Interfaces\CourseManager;
use App\Services\Interfaces\ExamBlockManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function app;
use function route;

class ExamBlockControllerTest extends TestCase {

    use RefreshDatabase;

    const FIXTURE_START_URI = "course/examblock";

    private CourseManager $courseManager;
    private ExamBlockManager $ebManager;
    private Course $course;

    protected function setUp(): void {
        parent::setUp();
        $this->course = Course::factory()->create();

        $this->ebManager = $this->createMock(ExamBlockManager::class);
        $this->courseManager = $this->createMock(CourseManager::class);
        app()->instance(CourseManager::class, $this->courseManager);
        app()->instance(ExamBlockManager::class, $this->ebManager);
    }

    public function test_index_authorization_forbidden() {
        $this->be(User::factory()->create());

        $response = $this->get(route("courseDetails", [$this->course->id]));
        $response->assertForbidden();
    }

    public function test_index_auth_admin_success() {
        $this->beAdmin();

        $course = Course::first();
        $this->courseManager->expects($this->once())
                ->method("getCourseFullDepth")
                ->with($course->id)
                ->willReturn($course);

        $response = $this->get(route("courseDetails", [$course->id]));
        $response->assertOk();
        $response->assertViewHas(["course" => $course]);
    }

    public function test_index_whenCouseIsMissing() {
        $this->beAdmin();

        $course = Course::first();
        $this->courseManager->expects($this->once())
                ->method("getCourseFullDepth")
                ->with($course->id)
                ->willReturn(null);

        $response = $this->get(route("courseDetails", [$course->id]));
        $response->assertRedirect(route("courseIndex"));
    }

    public function test_post_auth() {
        $this->be(User::factory()->create());

        $this->ebManager->expects($this->never())
                ->method("saveExamBlock");

        $response = $this->post(route("examBlockCreate", [$this->course->id]));
        $response->assertForbidden();
    }

    public function test_post_success() {
        $this->beAdmin();
        $ebInfo = new NewExamBlockInfo(3, 7, 2);

        $examBlock = ExamBlock::factory()->create();
        $this->ebManager->expects($this->once())
                ->method("saveExamBlock")
                ->with($ebInfo, $this->course->id)
                ->willReturn($examBlock);

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("examBlockCreate", [$this->course->id]), [
            "cfu" => 7,
            "maxExams" => 3,
            "courseYear" => 2
        ]);

        $response->assertOk()
                ->assertViewHas("examBlock", $examBlock);
    }

    public function test_post_validations() {
        $this->beAdmin();
        $this->ebManager->expects($this->never())
                ->method("saveExamBlock");

        $this->postValidationTest("cfu", "a4");
        $this->postValidationTest("cfu", null);
        $this->postValidationTest("maxExams", "de4");
        $this->postValidationTest("maxExams", null);
        $this->postValidationTest("courseYear", "s-3");
    }

    public function test_delete_auth() {
        $this->be(User::factory()->create());
        $examBlock = ExamBlock::factory()->create();

        $this->ebManager->expects($this->never())
                ->method("deleteExamBlock");

        $response = $this->delete(route("examBlockDelete", [$examBlock->id]));
        $response->assertForbidden();
    }

    public function test_delete_success() {
        $this->beAdmin();
        $examBlock = ExamBlock::factory()->create();

        $this->ebManager->expects($this->once())
                ->method("deleteExamBlock")
                ->with($examBlock->id);

        $response = $this->from(self::FIXTURE_START_URI)
                ->delete(route("examBlockDelete", [$examBlock->id]));

        $response->assertRedirect(self::FIXTURE_START_URI);
    }

    public function test_put_auth() {
        $this->be(User::factory()->create());
        $examBlock = ExamBlock::factory()->create();

        $this->ebManager->expects($this->never())
                ->method("updateExamBlock");

        $response = $this->put(route("examBlockUpdate", [$examBlock->id]));
        $response->assertForbidden();
    }

    public function test_put_whenEntityIsMissing() {
        $this->beAdmin();
        $examBlock = ExamBlock::factory()->create();
        $attributes = [
            "cfu" => 6,
            "maxExams" => 2,
            "courseYear" => 3
        ];

        $this->ebManager->expects($this->once())
                ->method("updateExamBlock")
                ->willThrowException(new ExamBlockNotFoundException());

        $response = $this->from(self::FIXTURE_START_URI)
                ->put(route("examBlockUpdate", [$examBlock->id]),$attributes);

        $response->assertRedirect(self::FIXTURE_START_URI);
    }

    public function test_put_success(){
        $this->beAdmin();
        $examBlock = ExamBlock::factory()->create();
        $attributes = [
            "cfu" => 6,
            "maxExams" => 2,
            "courseYear" => 3
        ];        
        
        $editedExamBlock = ExamBlock::factory()->make(["id" => 11]);
        $this->ebManager->expects($this->once())
                ->method("updateExamBlock")
                ->with(new NewExamBlockInfo(2, 6, 3))
                ->willReturn($editedExamBlock);
        
        $response = $this->from(self::FIXTURE_START_URI)
                ->put(route("examBlockUpdate",[$examBlock->id]), $attributes);
        
        $response->assertOk()
                ->assertViewHas("examBlock", $editedExamBlock);
    }
    
    public function test_put_validations() {
        $this->beAdmin();
        $examBlock = ExamBlock::factory()->create();
        $this->ebManager->expects($this->never())
                ->method("updateExamBlock");

        $this->putValidationTest("cfu", "a4", $examBlock->id);
        $this->putValidationTest("cfu", null, $examBlock->id);
        $this->putValidationTest("maxExams", "de4", $examBlock->id);
        $this->putValidationTest("maxExams", null, $examBlock->id);
        $this->putValidationTest("courseYear", "s-3", $examBlock->id);
    }

    private function beAdmin(): User {
        $roleAdmin = Role::create([
                    "name" => Role::ADMIN
        ]);
        $admin = User::factory()->create();
        $admin->roles()->attach($roleAdmin);
        $this->be($admin);
        return $admin;
    }

    private function postValidationTest(string $attrName, $attrValue) {
        $ebAttributes = [
            "cfu" => 7,
            "courseYear" => 3,
            "maxExams" => 2
        ];
        $ebAttributes[$attrName] = $attrValue;

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("examBlockCreate", [$this->course->id]), $ebAttributes);

        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    private function putValidationTest(string $attrName, $attrValue, int $blockId) {
        $ebAttributes = [
            "cfu" => 7,
            "courseYear" => 3,
            "maxExams" => 2
        ];
        $ebAttributes[$attrName] = $attrValue;

        $response = $this->from(self::FIXTURE_START_URI)
                ->put(route("examBlockUpdate", [$blockId]), $ebAttributes);

        $response->assertRedirect(self::FIXTURE_START_URI);
    }

}
