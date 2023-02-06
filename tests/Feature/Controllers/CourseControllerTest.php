<?php

namespace Tests\Feature\Controllers;

use App\Exceptions\Custom\CourseNameAlreadyExistsException;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use App\Services\Interfaces\CourseManager;
use App\Services\Interfaces\CoursesAdminManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use function app;
use function route;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;
    
    const FIXTURE_START_URI = "course";
    
    private CourseManager $courseManager;
    private array $courseAttributes;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->course = Course::factory()->create();
        
        $this->courseManager = $this->createMock(CourseManager::class);
        app()->instance(CourseManager::class, $this->courseManager);
        
        $this->courseAttributes = [
            "name" => "test name",
            "cfu" => 6,
            "maxRecognizedCfu" => 100,
            "otherActivitiesCfu" => 15,
            "finalExamCfu" => 10,
            "numberOfYears" => 3,
            "cfuTresholdForYear" => 40
        ];
    }
    
    public function test_authorizations_forbidden(){
        $this->be(User::factory()->create());
        
        $this->courseManager->expects($this->never())
                ->method("removeCourse");
        $this->courseManager->expects($this->never())
                ->method("updateCourse");
        $course = Course::first();
        
        $this->get(route("courseIndex"))->assertStatus(403);
        $this->post(route("courseCreate"),[new Course([])])->assertForbidden();
        $this->from((route("courseIndex")))->delete(route("courseDelete",[$course]))
                ->assertForbidden();
        $this->from((route("courseIndex")))->put(route("courseUpdate",[$course]))
                ->assertForbidden();
        $this->get(route("courseShow",[$course->id]))->assertForbidden();
        $this->get(route("courseNew"))->assertForbidden();
        $this->put(route("courseActivate",[$course->id]))->assertForbidden();
    }
    
    public function test_index_auth_admin_success(){
        $this->beAdmin();
        
        Course::factory(3)->create();
        $courses = Course::all();
        $this->courseManager->expects($this->once())
                ->method("getallCourses")
                ->with([])
                ->willReturn($courses);
        
        $response = $this->get(route("courseIndex"));
        $response->assertOk();
        $response->assertViewHas(["courses" => $courses]);
    }
    
    public function test_index_withFilters(){
        $this->beAdmin();
        $filters = ["search" => "corso"];
        
        Course::factory(3)->create();
        $courses = Course::all();
        $this->courseManager->expects($this->once())
                ->method("getallCourses")
                ->with($filters)
                ->willReturn($courses);
        
        $response = $this->get(route("courseIndex",$filters));
        $response->assertOk();
        $response->assertViewHas(["courses" => $courses]);
    }
    
    public function test_create_course_success(){
        $this->beAdmin();
        $course = new Course($this->courseAttributes);
        $this->courseManager->expects($this->once())
                ->method("addCourse")
                ->with($course);
        
        $response = $this->post(route("courseCreate"),$this->courseAttributes);
        $response->assertRedirectToRoute("courseIndex");
    }
    
    public function test_create_course_validations(){
        $this->beAdmin();
        Course::factory()->create(["name" => "existing name"]);
        $this->courseManager->expects($this->never())
                ->method("addCourse");
        
        $this->performPostValidationTest("name",null);
        $this->performPostValidationTest("cfu",null);
        $this->performPostValidationTest("cfu","ci4");
        $this->performPostValidationTest("finalExamCfu",null);
        $this->performPostValidationTest("finalExamCfu","4r");
        $this->performPostValidationTest("maxRecognizedCfu","4r");
        $this->performPostValidationTest("otherActivitiesCfu","4r");
        $this->performPostValidationTest("numberOfYears","4r");
        $this->performPostValidationTest("numberOfYears",null);
        $this->performPostValidationTest("cfuTresholdForYear",null);
        $this->performPostValidationTest("cfuTresholdForYear","ch6");
    }
    
    public function test_create_course_duplicateName(){
        $this->beAdmin();
        $this->courseManager->expects($this->once())
                ->method("addCourse")
                ->willThrowException(new CourseNameAlreadyExistsException("test message"));
        
        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("courseCreate"),$this->courseAttributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI)
                ->assertSessionHasErrors(["name" => "test message"]);
    }
        
    public function test_deleteCourse(){
        $this->beAdmin();
        $course = Course::first();
        $this->courseManager->expects($this->once())
                ->method("removeCourse")
                ->with($course->id);
        
        $response = $this->from((route("courseIndex")))
                ->delete(route("courseDelete",[$course->id]));
        $response->assertRedirectToRoute("courseIndex");
    }
    
    public function test_updateCourse_success(){
        $this->beAdmin();
        $course = Course::first();
        $newCourse = new Course($this->courseAttributes);
        $newCourse->id = $course->id;
        
        $this->courseManager->expects($this->once())
                ->method("updateCourse")
                ->with($newCourse);
        
        $response = $this->put(
                route("courseUpdate",[Course::first()]),$this->courseAttributes);
        $response->assertRedirectToRoute("courseShow",[$course->id]);
    }
    
    public function test_updateCourse_duplicateName(){
        $this->beAdmin();
        $course = Course::first();
        $newCourse = new Course($this->courseAttributes);
        $newCourse->id = $course->id;
        
        $this->courseManager->expects($this->once())
                ->method("updateCourse")
                ->willThrowException(new CourseNameAlreadyExistsException("test message"));
        
        $response = $this->from(self::FIXTURE_START_URI)->put(
                route("courseUpdate",[$course->id]),$this->courseAttributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI)
                ->assertSessionHasErrors(["name" => "test message"]);
    }
    
    public function test_updateCourseForm(){
        $this->beAdmin();
        $course = Course::first();
        
        $response = $this->get(route("courseShow",[$course->id]));
        
        $response->assertViewIs("courses.input")
                ->assertViewHas("course", $course)
                ->assertViewHas("action", route("courseUpdate",[$course->id]));
    }
    
    public function test_newCourseForm(){
        $this->beAdmin();
        $response = $this->get(route("courseNew"));
        
        $response->assertViewIs("courses.input")
            ->assertViewMissing("course")
            ->assertViewHas("action", route("courseCreate"));
    }
    
    public function test_activateCourse_true(){
        $this->beAdmin();
        
        $this->courseManager->expects($this->once())
                ->method("setCourseActive")
                ->with(5, true);
        
        $this->put(route("courseActivate",[5]),["active" => "on"])
                ->assertNoContent();
    }
    
    public function test_activateCourse_false(){
        $this->beAdmin();
        
        $this->courseManager->expects($this->once())
                ->method("setCourseActive")
                ->with(5, false);
        
        $this->put(route("courseActivate",[5]))
                ->assertNoContent();
    }
    
    private function beAdmin(): User{
        $roleAdmin = Role::create([
            "name" => Role::ADMIN
        ]);
        $admin = User::factory()->create();
        $admin->roles()->attach($roleAdmin);
        $this->be($admin);
        return $admin;
    }
    
    private function performPostValidationTest(string $attrName, $attrValue): TestResponse{
        $localAttributes = [
            "name" => "test name",
            "cfu" => 6,
            "maxRecognizedCfu" => 100,
            "otherActivitiesCfu" => 15,
            "finalExamCfu" => 10,
            "numberOfYears" => 3,
            "cfuTresholdForYear" => 40
        ];
        $localAttributes[$attrName] = $attrValue;
        
        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("courseCreate"),$localAttributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI)
                ->assertSessionHasErrors($attrName);
        return $response;
    }
}
