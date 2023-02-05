<?php

namespace Tests\Feature\Controllers;

use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Models\Role;
use App\Models\Ssd;
use App\Models\User;
use App\Services\Interfaces\ExamBlockManager;
use App\Services\Interfaces\ExamBlockSsdManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function app;
use function route;

class ExamBlockSsdControllerTest extends TestCase {

    use RefreshDatabase;

    const FIXTURE_START_URI = "course/examblock/ssds";

    private ExamBlockManager $ebSsdManager;

    protected function setUp(): void {
        parent::setUp();

        ExamBlock::factory()->create([
            "course_id" => Course::factory()->create()
        ]);
        $this->ebSsdManager= $this->createMock(ExamBlockManager::class);
        app()->instance(ExamBlockManager::class, $this->ebSsdManager);
    }

    public function test_authorization_forbidden() {
        $this->be(User::factory()->create());

        $this->get(route("examBlockSsds", [ExamBlock::first()->id]))
            ->assertForbidden();
        $this->post(route("addExamBlockSsd", [ExamBlock::first()->id]))
            ->assertForbidden();
        $this->delete(route("delExamBlockSsd", [
                ExamBlock::first()->id,
                Ssd::factory()->create()->id]))
            ->assertForbidden();
    }
    
    public function test_show_success(){
        $this->beAdmin();
        $examBlock = ExamBlock::first();
        
        $this->ebSsdManager->expects($this->once())
                ->method("eagerLoadExamBlock")
                ->with($examBlock->id)
                ->willReturn($examBlock);
        
        $this->get(route("examBlockSsds",[$examBlock->id]))
            ->assertViewHas(["examBlock" => $examBlock]);
    }
    
    public function test_show_whenExamBlock_isMissing(){
        $this->beAdmin();
        
        $this->ebSsdManager->expects($this->once())
                ->method("eagerLoadExamBlock")
                ->with(5)
                ->willThrowException(new ExamBlockNotFoundException("error message"));
        
        $this->from(self::FIXTURE_START_URI)
                ->get(route("examBlockSsds",[5]))
                ->assertRedirect(self::FIXTURE_START_URI)
                ->assertSessionHas("error", "error message");
    }
    
    public function test_post_whenExamBlock_isMissing(){
        $this->beAdmin();
        
        $this->ebSsdManager->expects($this->once())
                ->method("addSsd")
                ->with(3,"testCode")
                ->willThrowException(new ExamBlockNotFoundException("test error"));
        
        $this->post(route("addExamBlockSsd",[3]),["ssd" => "testCode"])
            ->assertRedirectToRoute("courseIndex")
            ->assertSessionHas("error", "test error");
    }
    
//    public function test_post_validations(){
//        $this->beAdmin();
//        $examBlock = ExamBlock::first();
//        
//        $this->ebSsdManager->expects($this->never());
//        
//        $this->ebSsdManager->expects($this->once())
//                ->method("addSsd")
//                ->with($examBlock->id, "weird code");
//        
//        $this->from(self::FIXTURE_START_URI)
//                ->post(route("addExamBlockSsd",[$examBlock->id]),["ssd" => $ssd->code])
//                ->assertRedirectToRoute(self::FIXTURE_START_URI);
//        
//    }
    
    public function test_post_success(){
        $this->beAdmin();
        $ssd = Ssd::factory()->create();
        
        $this->ebSsdManager->expects($this->once())
                ->method("addSsd")
                ->with(5,$ssd->code);
        
        $this->from(self::FIXTURE_START_URI)
                ->post(route("addExamBlockSsd",[5]),["ssd" => $ssd->code])
                ->assertRedirect(self::FIXTURE_START_URI);
    }
//    public function test_index_auth_admin_success() {
//        $this->beAdmin();
//
//        $course = Course::first();
//        $this->courseManager->expects($this->once())
//                ->method("getCourseFullData")
//                ->with($course->id)
//                ->willReturn($course);
//
//        $response = $this->get(route("courseDetails", [$course->id]));
//        $response->assertOk();
//        $response->assertViewHas(["course" => $course]);
//    }
//
//    public function test_index_whenCouseIsMissing() {
//        $this->beAdmin();
//
//        $course = Course::first();
//        $this->courseManager->expects($this->once())
//                ->method("getCourseFullData")
//                ->with($course->id)
//                ->willReturn(null);
//
//        $response = $this->get(route("courseDetails", [$course->id]));
//        $response->assertRedirect(route("courseIndex"));
//    }
//
//    public function test_post_auth() {
//        $this->be(User::factory()->create());
//
//        $this->courseManager->expects($this->never())
//                ->method("saveExamBlock");
//
//        $response = $this->post(route("examBlockCreate", [$this->course->id]));
//        $response->assertForbidden();
//    }
//
//    public function test_post_success() {
//        $this->beAdmin();
//        $ebInfo = new NewExamBlockInfo(3, 7, 2);
//
//        $this->courseManager->expects($this->once())
//                ->method("saveExamBlock")
//                ->with($ebInfo, $this->course->id);
//
//        $response = $this->from(self::FIXTURE_START_URI)
//                ->post(route("examBlockCreate", [$this->course->id]), [
//            "cfu" => 7,
//            "maxExams" => 3,
//            "courseYear" => 2
//        ]);
//
//        $response->assertRedirect(self::FIXTURE_START_URI);
//    }
//
//    public function test_post_validations() {
//        $this->beAdmin();
//        $this->courseManager->expects($this->never())
//                ->method("saveExamBlock");
//
//        $this->postValidationTest("cfu", "a4");
//        $this->postValidationTest("cfu", null);
//        $this->postValidationTest("maxExams", "de4");
//        $this->postValidationTest("maxExams", null);
//        $this->postValidationTest("courseYear", "s-3");
//    }
//
//    public function test_delete_auth() {
//        $this->be(User::factory()->create());
//        $examBlock = ExamBlock::factory()->create();
//
//        $this->courseManager->expects($this->never())
//                ->method("deleteExamBlock");
//
//        $response = $this->delete(route("examBlockDelete", [$examBlock->id]));
//        $response->assertForbidden();
//    }
//
//    public function test_delete_success() {
//        $this->beAdmin();
//        $examBlock = ExamBlock::factory()->create();
//
//        $this->courseManager->expects($this->once())
//                ->method("deleteExamBlock")
//                ->with($examBlock->id);
//
//        $response = $this->from(self::FIXTURE_START_URI)
//                ->delete(route("examBlockDelete", [$examBlock->id]));
//
//        $response->assertRedirect(self::FIXTURE_START_URI);
//    }
//
//    public function test_put_auth() {
//        $this->be(User::factory()->create());
//        $examBlock = ExamBlock::factory()->create();
//
//        $this->courseManager->expects($this->never())
//                ->method("updateExamBlock");
//
//        $response = $this->put(route("examBlockUpdate", [$examBlock->id]));
//        $response->assertForbidden();
//    }
//
//    public function test_put_whenEntityIsMissing() {
//        $this->beAdmin();
//        $examBlock = ExamBlock::factory()->create();
//        $attributes = [
//            "cfu" => 6,
//            "maxExams" => 2,
//            "courseYear" => 3
//        ];
//
//        $this->courseManager->expects($this->once())
//                ->method("updateExamBlock")
//                ->willThrowException(new ExamBlockNotFoundException());
//
//        $response = $this->from(self::FIXTURE_START_URI)
//                ->put(route("examBlockUpdate", [$examBlock->id]),$attributes);
//
//        $response->assertRedirect(self::FIXTURE_START_URI);
//    }
//
//    public function test_put_success(){
//        $this->beAdmin();
//        $examBlock = ExamBlock::factory()->create();
//        $attributes = [
//            "cfu" => 6,
//            "maxExams" => 2,
//            "courseYear" => 3
//        ];        
//        
//        $this->courseManager->expects($this->once())
//                ->method("updateExamBlock")
//                ->with(new NewExamBlockInfo(2, 6, 3));
//        
//        $response = $this->from(self::FIXTURE_START_URI)
//                ->put(route("examBlockUpdate",[$examBlock->id]), $attributes);
//        
//        $response->assertRedirect(self::FIXTURE_START_URI);
//    }
//    
//    public function test_put_validations() {
//        $this->beAdmin();
//        $examBlock = ExamBlock::factory()->create();
//        $this->courseManager->expects($this->never())
//                ->method("updateExamBlock");
//
//        $this->putValidationTest("cfu", "a4", $examBlock->id);
//        $this->putValidationTest("cfu", null, $examBlock->id);
//        $this->putValidationTest("maxExams", "de4", $examBlock->id);
//        $this->putValidationTest("maxExams", null, $examBlock->id);
//        $this->putValidationTest("courseYear", "s-3", $examBlock->id);
//    }
//
    private function beAdmin(): User {
        $roleAdmin = Role::create([
                    "name" => Role::ADMIN
        ]);
        $admin = User::factory()->create();
        $admin->roles()->attach($roleAdmin);
        $this->be($admin);
        return $admin;
    }
//
//    private function postValidationTest(string $attrName, $attrValue) {
//        $ebAttributes = [
//            "cfu" => 7,
//            "courseYear" => 3,
//            "maxExams" => 2
//        ];
//        $ebAttributes[$attrName] = $attrValue;
//
//        $response = $this->from(self::FIXTURE_START_URI)
//                ->post(route("examBlockCreate", [$this->course->id]), $ebAttributes);
//
//        $response->assertRedirect(self::FIXTURE_START_URI);
//    }
//    
//    private function putValidationTest(string $attrName, $attrValue, int $blockId) {
//        $ebAttributes = [
//            "cfu" => 7,
//            "courseYear" => 3,
//            "maxExams" => 2
//        ];
//        $ebAttributes[$attrName] = $attrValue;
//
//        $response = $this->from(self::FIXTURE_START_URI)
//                ->put(route("examBlockUpdate", [$blockId]), $ebAttributes);
//
//        $response->assertRedirect(self::FIXTURE_START_URI);
//    }

}
