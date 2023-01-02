<?php

namespace Tests\Feature\Controllers;

use App\Http\Controllers\CourseController;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use App\Services\Interfaces\CoursesAdminManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function app;
use function route;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;
    
    const FIXTURE_START_URI = "course";
    
    private CoursesAdminManager $coursesManager;
    private array $courseAttributes;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->course = Course::factory()->create();
        
        $this->coursesManager = $this->createMock(CoursesAdminManager::class);
        app()->instance(CoursesAdminManager::class, $this->coursesManager);
        
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
    
    public function test_index_authorization_forbidden(){
        $this->be(User::factory()->create());
        
        $response = $this->get(route("courseIndex"));
        $response->assertStatus(403);
    }
    
    public function test_index_auth_admin_success(){
        $this->beAdmin();
        
        Course::factory(3)->create();
        $courses = Course::all();
        $this->coursesManager->expects($this->once())
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
        $this->coursesManager->expects($this->once())
                ->method("getallCourses")
                ->with($filters)
                ->willReturn($courses);
        
        $response = $this->get(route("courseIndex",$filters));
        $response->assertOk();
        $response->assertViewHas(["courses" => $courses]);
    }
    
    public function test_create_course_auth(){
        $this->be(User::factory()->create());
        
        $course = new Course(["name" => "test name"]);
        
        $response = $this->post(route("courseCreate"),[$course]);
        $response->assertForbidden();
    }
    
    public function test_create_course_success(){
        $this->beAdmin();
        $course = new Course($this->courseAttributes);
        $this->coursesManager->expects($this->once())
                ->method("addCourse")
                ->with($course);
        
        $response = $this->from((route("courseIndex")))
                ->post(route("courseCreate"),$this->courseAttributes);
        $response->assertRedirect(route("courseIndex"));
    }
    
    public function test_create_course_name_required(){
        $this->performValidationTest("name",null);
    }
    
    public function test_create_course_cfu_required(){
        $this->performValidationTest("cfu",null);
    }
    
    public function test_create_course_cfu_mustBeNumber(){
        $this->performValidationTest("cfu","ci4");
    }
    
    public function test_create_course_finaExamCfu_required(){
        $this->performValidationTest("finalExamCfu",null);
    }
        
    public function test_create_course_finaExamCfu_mustBeNumber(){
        $this->performValidationTest("finalExamCfu","4r");
    }
    
    public function test_create_course_maxRecognizedCfu_mustBeNumber(){
        $this->performValidationTest("maxRecognizedCfu","4r");
    }
    
    public function test_create_course_otherActivities_mustBeNumber(){
        $this->performValidationTest("otherActivitiesCfu","4r");
    }
    
    public function test_create_course_numberOfYears_mustBeNumber(){
        $this->performValidationTest("numberOfYears","4r");
    }
    
    public function test_create_course_numberOfYears_isRequired(){
        $this->performValidationTest("numberOfYears",null);
    }
    
    public function test_create_course_cfuTreshold_isRequired(){
        $this->performValidationTest("cfuTresholdForYear",null);
    }
    
    public function test_create_course_cfuTreshold_mustBeNumber(){
        $this->performValidationTest("cfuTresholdForYear","ch6");
    }
    
    public function test_deleteCourse(){
        $this->beAdmin();
        $course = Course::first();
        $this->coursesManager->expects($this->once())
                ->method("removeCourse")
                ->with($course->id);
        
        $response = $this->from((route("courseIndex")))
                ->delete(route("courseDelete",[$course->id]));
        $response->assertRedirect(route("courseIndex"));
    }
    
    public function test_deleteCourse_auth(){
        $this->be(User::factory()->create());
        
        $this->coursesManager->expects($this->never())
                ->method("removeCourse");
        
        $response = $this->from((route("courseIndex")))
                ->delete(route("courseDelete",[Course::first()]));
        $response->assertForbidden();
    }
    
    public function test_updateCourse_auth(){
        $this->be(User::factory()->create());
        
        $this->coursesManager->expects($this->never())
                ->method("updateCourse");
        
        $response = $this->from((route("courseIndex")))
                ->put(route("courseUpdate",[Course::first()]));
        $response->assertForbidden();
    }
    
    public function test_updateCourse_success(){
        $this->beAdmin();
        $course = Course::first();
        $newCourse = new Course($this->courseAttributes);
        $newCourse->id = $course->id;
        
        $this->coursesManager->expects($this->once())
                ->method("updateCourse")
                ->with($newCourse);
        
        $response = $this->from((route("courseIndex")))
                ->put(route("courseUpdate",[Course::first()]),$this->courseAttributes);
        $response->assertRedirect(route("courseIndex"));
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
    
    private function performValidationTest(string $attrName, $attrValue){
        $this->beAdmin();
        $this->courseAttributes[$attrName] = $attrValue;
        
        $this->coursesManager->expects($this->never())
                ->method("addCourse");
        
        $response = $this->from((route("courseIndex")))
                ->post(route("courseCreate"),$this->courseAttributes);
        
        $response->assertRedirect(route("courseIndex"));
    }
}
