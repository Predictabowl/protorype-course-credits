<?php

namespace Tests\Feature\Controllers;


use App\Models\Course;
use App\Models\Front;
use App\Models\User;
use App\Domain\StudyPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Interfaces\StudyPlanManager;
use App\Factories\Interfaces\StudyPlanManagerFactory;

use Tests\TestCase;

class StudyPlanControllerTest extends TestCase
{
    use RefreshDatabase;
    
    const FIXTURE_START_URI = "front";
    
    private $user;
    private $front;
    private $manager;
    
    
    protected function setUp(): void {
        parent::setUp();
        
            $this->user = User::factory()->create();
            $this->front = Front::create(["user_id" => $this->user->id]);
            
            $this->manager = $this->createMock(StudyPlanManager::class);
            $factory = $this->createMock(StudyPlanManagerFactory::class);
            $factory->expects($this->any())
                    ->method("get")
                    ->willReturn($this->manager);
            app()->instance(StudyPlanManagerFactory::class, $factory);
    }
    
    
    public function test_authentication_required(){
        $this->get(route("studyPlan",[$this->front]))
            ->assertRedirect(route("login"));
        
        $this->get(route("studyPlanPdf",[$this->front]))
            ->assertRedirect(route("login"));
    }

    public function test_show_authorization(){
        $user2 = User::factory()->create();
        $response =  $this->actingAs($user2)
                ->get(route("studyPlan",[$this->front]));
        
        $response->assertForbidden();
    }
    
    public function test_show_with_course_not_set(){
        $this->manager->expects($this->once())
                ->method("getStudyPlan")
                ->willReturn(null);
        
        $response =  $this->actingAs($this->user)
                ->from(self::FIXTURE_START_URI)
                ->get(route("studyPlan",[$this->front]));
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_show(){
        $this->front->course()->associate(Course::factory()->create())
                ->save();
        $plan = new StudyPlan(collect([]));
        $this->manager->expects($this->once())
                ->method("getStudyPlan")
                ->willReturn($plan);
        $this->manager->expects($this->once())
                ->method("getAcademicYear")
                ->willReturn(2012);
        $this->manager->expects($this->once())
                ->method("getCourseYear")
                ->willReturn(2);
        
        $response =  $this->actingAs($this->user)
                ->from(self::FIXTURE_START_URI)
                ->get(route("studyPlan",[$this->front]));
        
        $response->assertOk()
            ->assertViewHas([
                "front" => $this->front,
                "studyPlan" => $plan,
                "academicYear" => 2012,
                "courseYear" => 2
            ])
            ->assertSessionHas([
                "studyPlan" => $plan,
                "academicYear" => 2012,
                "courseYear" => 2
            ]);
    }
    
    public function test_createPdf_Ok(){
        $course = Course::factory()->create();
        $this->front->course()->associate($course);
        $this->front->save();
        session()->put("studyPlan",new StudyPlan(collect([])));
        
        $response =  $this->actingAs($this->user)
                ->get(route("studyPlanPdf",[$this->front]));
        
        $response->assertOk();
    }
    
}
