<?php

namespace Tests\Feature\Controllers;

use App\Domain\NewExamInfo;
use App\Exceptions\Custom\ExamNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\Role;
use App\Models\Ssd;
use App\Models\User;
use App\Services\Interfaces\CourseAdminManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function app;
use function route;

class ExamControllerTest extends TestCase {

    use RefreshDatabase;

    const FIXTURE_START_URI = "course/examblock/exam";
    const FIXTURE_SSD = "IUS/10";

    private CourseAdminManager $courseManager;
    private ExamBlock $examBlock;
    private Ssd $ssdFixture;

    protected function setUp(): void {
        parent::setUp();
        Course::factory()->create();
        $this->examBlock = ExamBlock::factory()->create();
        $this->ssdFixture = Ssd::factory()->create([
            "code" => self::FIXTURE_SSD
        ]);

        $this->courseManager = $this->createMock(CourseAdminManager::class);
        app()->instance(CourseAdminManager::class, $this->courseManager);
    }

    public function test_post_auth() {
        $this->be(User::factory()->create());

        $this->courseManager->expects($this->never())
                ->method("saveExam");

        $this->post(route("examCreate", [$this->examBlock->id]))
            ->assertForbidden();
    }

    public function test_post_success() {
        $this->beAdmin();
        $examInfo = new NewExamInfo("test",$this->ssdFixture->code,false);

        $returnedExam = Exam::factory()->make([
            "id" => 3,
            "free_choice" => false
            ]);
        $this->courseManager->expects($this->once())
                ->method("saveExam")
                ->with($examInfo, $this->examBlock->id)
                ->willReturn($returnedExam);

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("examCreate", [$this->examBlock->id]), [
            "name" => "test",
            "ssd" => $this->ssdFixture->code]);

        $response->assertOk()
                ->assertViewHas("exam", $returnedExam);
    }

    public function test_post_validations() {
        $this->beAdmin();
        $this->courseManager->expects($this->never())
                ->method("saveExam");

        $this->postValidationTest("name", "");
        $this->postValidationTest("name", null);
        $this->postValidationTest("ssd", "I2US/07");
        $this->postValidationTest("ssd", "");
    }
    
    public function test_post_ssdNotRequired_ifFreeChoice() {
        $this->beAdmin();
        $examInfo = new NewExamInfo("test name", null, true);

        $examAttributes = [
            "name" => "test name",
            "freeChoice" => true,
            "ssd" => "anything"
        ];
        
        $returnedExam = Exam::factory()->make([
            "id" => 3,
            "ssd_id" => null,
            "free_choice" => true
            ]);
        $this->courseManager->expects($this->once())
                ->method("saveExam")
                ->with($examInfo, $this->examBlock->id)
                ->willReturn($returnedExam);

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("examCreate", [$this->examBlock->id]), $examAttributes);

        $response->assertOk()
                ->assertViewHas("exam", $returnedExam);
        
    }
    
    public function test_post_whenSsdIsMissing_shouldReturnError(){
        $this->beAdmin();
        $examInfo = new NewExamInfo("test",$this->ssdFixture->code,false);

        $this->courseManager->expects($this->once())
                ->method("saveExam")
                ->with($examInfo, $this->examBlock->id)
                ->willThrowException(new SsdNotFoundException("test error"));

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("examCreate", [$this->examBlock->id]), [
            "name" => "test",
            "ssd" => $this->ssdFixture->code]);

        $response->assertUnprocessable();
    }

    public function test_delete_auth() {
        $this->be(User::factory()->create());
        $exam = Exam::factory()->create();

        $this->courseManager->expects($this->never())
                ->method("deleteExam");

        $this->delete(route("examDelete", [$exam->id]))
            ->assertForbidden();
    }

    public function test_delete_success() {
        $this->beAdmin();
        $exam = Exam::factory()->create();

        $this->courseManager->expects($this->once())
                ->method("deleteExam")
                ->with($exam->id);

        $this->from(self::FIXTURE_START_URI)
                ->delete(route("examDelete", [$exam->id]))
                ->assertNoContent();
    }

    public function test_put_auth() {
        $this->be(User::factory()->create());
        $exam = Exam::factory()->create();

        $this->courseManager->expects($this->never())
                ->method("updateExam");

        $this->put(route("examUpdate", [$exam->id]))
            ->assertForbidden();
    }

    public function test_put_whenExamBlockIsMissing_shouldReturnNotFound() {
        $this->beAdmin();
        $exam = Exam::factory()->create();
        
        $attributes = [
            "name" => "test",
            "freeChoice" => true,
        ];

        $this->courseManager->expects($this->once())
                ->method("updateExam")
                ->willThrowException(new ExamNotFoundException());

        $response = $this->from(self::FIXTURE_START_URI)
                ->put(route("examUpdate", [$exam->id]),$attributes);

        $response->assertNotFound();
    }

    public function test_put_success(){
        $this->beAdmin();
        $exam = Exam::factory()->create();
        $attributes = [
            "name" => "test",
            "ssd" => $this->ssdFixture->code
        ];
        
        $this->courseManager->expects($this->once())
                ->method("updateExam")
                ->with(new NewExamInfo("test", self::FIXTURE_SSD, false),
                        $exam->id);
        
        $response = $this->from(self::FIXTURE_START_URI)
                ->put(route("examUpdate",[$exam->id]), $attributes);
        
        $response->assertNoContent();
    }
    
    public function test_put_whenSsdIsMissing_shouldReturnError(){
        $this->beAdmin();
        $exam = Exam::factory()->create();
        $attributes = [
            "name" => "test",
            "ssd" => $this->ssdFixture->code
        ];
        
        $this->courseManager->expects($this->once())
                ->method("updateExam")
                ->with(new NewExamInfo("test", self::FIXTURE_SSD, false),
                        $exam->id)
                ->willThrowException(new SsdNotFoundException("error msg"));
        
        $response = $this->from(self::FIXTURE_START_URI)
                ->put(route("examUpdate",[$exam->id]), $attributes);
        
        $response->assertUnprocessable();
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
        $examAttributes = [
            "name" => "test name",
            "ssd" => self::FIXTURE_SSD
        ];
        $examAttributes[$attrName] = $attrValue;

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("examCreate", [$this->examBlock->id]), $examAttributes);

        $response->assertUnprocessable();        
    }

}
