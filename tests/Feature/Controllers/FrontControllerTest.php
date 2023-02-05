<?php

namespace Tests\Feature\Controllers;

use App\Domain\TakenExamDTO;
use App\Models\Course;
use App\Models\Front;
use App\Models\Role;
use App\Models\User;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\FrontsSearchManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function app;
use function collect;
use function route;

class FrontControllerTest extends TestCase
{
    use RefreshDatabase;
    
    const FIXTURE_START_URI = "front";
    
    private FrontsSearchManager $searchManager;
    private FrontManager $frontManager;
    private User $user;
    private Front $front;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->front = Front::create([
            "user_id" => $this->user->id
        ]);
        
        $this->frontManager = $this->createMock(FrontManager::class);
        $this->searchManager = $this->createMock(FrontsSearchManager::class);
        app()->instance(FrontManager::class, $this->frontManager);
        app()->instance(FrontsSearchManager::class, $this->searchManager);
    }
    
    public function test_access_redirect_without_authentication(){
        $this->get(route("frontIndex",1))->assertRedirect(route("login"));
        
        $this->get(route("frontView",1))->assertRedirect(route("login"));
        
        $this->put(route("frontView",1), [])->assertRedirect(route("login"));
    }
    
    public function test_index_authorization(){
        $this->be($this->user);
        
        $response = $this->get(route("frontIndex"));
        $response->assertStatus(403);
    }
    
    public function test_show_authorization(){
        $this->be($this->user);

        $user2 = User::factory()->create();
        $front2 = Front::create(["user_id" => $user2->id]);
                
        $response = $this->get(route("frontView",[$front2]));
        $response->assertStatus(403);
        
    }
    
    public function test_put_authorization(){
        $course = Course::factory()->create();
        
        $response = $this->from(self::FIXTURE_START_URI)
                ->actingAs($this->user)
                ->put(route("frontView",[$this->front]),["courseId" => $course->id]);
        $response->assertRedirect(self::FIXTURE_START_URI);

        $user2 = User::factory()->create();
                
        $response = $this->actingAs($user2)
                ->put(route("frontView",[$this->front]),["courseId" => $course->id]);
        $response->assertStatus(403);
        
        $this->makeUserSupervisor($user2);
        $response = $this->actingAs($user2)
                ->put(route("frontView",[$this->front]),["courseId" => $course->id]);
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_index(){
        $courses = Course::factory(5)->create();
        $page = Front::paginate();
        $this->be($this->user);
        $this->makeUserSupervisor();
        $this->searchManager->expects($this->once())
                ->method("getFilteredFronts")
                ->willReturn($page);
                
        $this->searchManager->expects($this->once())
                ->method("getCourses")
                ->willReturn($courses);

        $this->searchManager->expects($this->once())
                ->method("getCurrentCourse")
                ->willReturn($courses[0]);
        
        $response = $this->get(route("frontIndex"));
        
        $response->assertViewHas([
            "currentCourse" => $courses[0],
            "courses" => $courses,
            "fronts" => $page]);
                
    }
    
    public function test_show(){
        $courses = Course::factory(5)->create();
        $exams = collect([
            new TakenExamDTO(1, "test 1", "ssd1", 8, 19),
            new TakenExamDTO(2, "test 2", "ssd2", 9, 20)
        ]);
        $this->be($this->user);
        $this->frontManager->expects($this->once())
                ->method("getTakenExams")
                ->with($this->front->id)
                ->willReturn($exams);
                
        $this->searchManager->expects($this->once())
                ->method("getCourses")
                ->willReturn($courses);
        
        $response = $this->get(route("frontView",[$this->front]));
        
        
        $response->assertOk();
        $response->assertViewHas([
            "exams" => $exams,
            "courses" => $courses,
            "front" => $this->front]);
                
    }
    
    public function test_put(){
        $this->be($this->user);
        $this->frontManager->expects($this->once())
                ->method("setCourse")
                ->with($this->front->id, 3);
                
        $response = $this->from(self::FIXTURE_START_URI)
                ->put(route("frontView",[$this->front]),["courseId" => 3]);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    private function makeUserSupervisor(?User $user = null){
        if (!isset($user)){
            $user = $this->user;
        }
        $role = Role::create([
            "name" => Role::SUPERVISOR
        ]);
        $user->roles()->attach($role);
    }
    
}
