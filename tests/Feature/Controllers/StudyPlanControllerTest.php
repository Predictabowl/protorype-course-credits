<?php

namespace Tests\Feature\Controllers;


use App\Models\Course;
use App\Models\Front;
use App\Models\User;
use App\Domain\TakenExamDTO;
use App\Domain\StudyPlan;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\UserFrontManager;
use App\Factories\Interfaces\FrontManagerFactory;
use App\Services\Interfaces\FrontsSearchManager;

use Tests\TestCase;

class StudyPlanControllerTest extends TestCase
{
    use RefreshDatabase;
    
    const FIXTURE_START_URI = "front";
    
    private $user;
    private $front;
    
    
    protected function setUp(): void {
        parent::setUp();
        
            $this->user = User::factory()->create();
            $this->front = Front::create(["user_id" => $this->user->id]);
    }
    
    
    public function test_authentication_required(){
        $response =  $this->get(route("studyPlan",[$this->front]));
        $response->assertRedirect(route("login"));
        
        $response =  $this->get(route("studyPlanPdf",[$this->front]));
        $response->assertRedirect(route("login"));
    }

    public function test_show_authorization(){
        $user2 = User::factory()->create();
        $response =  $this->actingAs($user2)
                ->get(route("studyPlan",[$this->front]));
        
        $response->assertForbidden();
    }
    
    public function test_show_with_course_not_set(){
        $userFrontManager = $this->createMock(UserFrontManager::class);
        app()->instance(UserFrontManager::class, $userFrontManager);
        
        $userFrontManager->expects($this->once())
                ->method("setUserId")
                ->with($this->user->id)
                ->willReturn($userFrontManager);
        
        $userFrontManager->expects($this->once())
                ->method("getStudyPlanBuilder")
                ->willReturn(null);
        
        $response =  $this->actingAs($this->user)
                ->from(self::FIXTURE_START_URI)
                ->get(route("studyPlan",[$this->front]));
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_show(){
        $course = Course::factory()->create();
        $this->front->course()->associate($course);
        $this->front->save();
        $userFrontManager = $this->createMock(UserFrontManager::class);
        app()->instance(UserFrontManager::class, $userFrontManager);
        
        $userFrontManager->expects($this->once())
                ->method("setUserId")
                ->with($this->user->id)
                ->willReturn($userFrontManager);
        
        $builder = $this->createMock(StudyPlanBuilder::class);
        $plan = new StudyPlan(collect([]));
        
        $userFrontManager->expects($this->once())
                ->method("getStudyPlanBuilder")
                ->willReturn($builder);
        
        $builder->expects($this->once())
                ->method("getStudyPlan")
                ->willReturn($plan);
        
        $response =  $this->actingAs($this->user)
                ->from(self::FIXTURE_START_URI)
                ->get(route("studyPlan",[$this->front]));
        
        $response->assertOk();
        $response->assertViewHas([
            "front" => $this->front,
            "studyPlan" => $plan
        ]);
    }
    
}