<?php

namespace Tests\Feature\Controllers;


use App\Models\TakenExam;
use App\Models\Front;
use App\Models\User;
use App\Models\Ssd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\Interfaces\FrontManager;
use App\Factories\Interfaces\FrontManagerFactory;

use Tests\TestCase;

class TakenExamControllerTest extends TestCase
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
    }
    
    public function test_access_redirect_without_authentication(){
        $response = $this->post(route("postTakenExam",1), []);
        
        $response->assertRedirect(route("login"));
        
        $response = $this->delete(route("deleteTakenExam",1), []);
        
        $response->assertRedirect(route("login"));
    }
    
    public function test_create_successful(){
        $ssd = Ssd::factory()->create();
        $attributes = [
            "name" => "exam 1",
            "cfu" => 5,
            "ssd" => $ssd->code
        ];
        $this->setupMocksAndAuth();
        $this-> manager->expects($this->once())
                ->method("saveTakenExam")
                ->with($attributes);

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $attributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
        
    }
    
    public function test_create_denied_by_policy_when_wrong_user(){
        $user2 = User::factory()->create();
        $ssd = Ssd::factory()->create();
        $attributes = [
            "name" => "exam 1",
            "cfu" => 5,
            "ssd" => $ssd->code
        ];
        $this->setupMocksAndAuth();
        $this-> manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->actingAs($user2)
                ->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $attributes);
        
        $response->assertStatus(403);
        
    }
    
    public function test_create_validation_name_missing(){
        $ssd = Ssd::factory()->create();
        $attributes = [
            "name" => null,
            "cfu" => 5,
            "ssd" => $ssd->code
        ];
       $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $attributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_create_validation_cfu_missing(){
        $ssd = Ssd::factory()->create();
        $attributes = [
            "name" => "test",
            "cfu" => null,
            "ssd" => $ssd->code
        ];
       $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $attributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_create_validation_cfu_not_numeric(){
        $ssd = Ssd::factory()->create();
        $attributes = [
            "name" => "test",
            "cfu" => "5a",
            "ssd" => $ssd->code
        ];
       $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $attributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_create_validation_ssd_missing(){
        $attributes = [
            "name" => "null",
            "cfu" => 6,
            "ssd" => null
        ];
       $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $attributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_create_validation_ssd_not_existing(){
        $ssd = Ssd::factory()->create();
        $attributes = [
            "name" => "null",
            "cfu" => 6,
            "ssd" => $ssd->id+1
        ];
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $attributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_delete_validation_with_id_not_present(){
        $attributes = [
            "id" => 1
        ];
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("deleteTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->delete(route("deleteTakenExam",[$this->front]), $attributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_delete_success(){
        $exam = TakenExam::factory()->create([
            "ssd_id" => Ssd::factory()->create()
        ]);
        $attributes = [
            "id" => $exam->id
        ];
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->once())
                ->method("deleteTakenExam")
                ->with($exam->id);

        $response = $this->from(self::FIXTURE_START_URI)
                ->delete(route("deleteTakenExam",[$this->front]), $attributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_delete_access_policy_with_no_authorized_user(){
        $user2 = User::factory()->create();
        $this->setupMocksAndAuth();
        
        $exam = TakenExam::factory()->create([
            "ssd_id" => Ssd::factory()->create()
        ]);
        $attributes = [
            "id" => $exam->id
        ];
        
        $this->manager->expects($this->never())
                ->method("deleteTakenExam");

        $response = $this->actingAs($user2)
                ->delete(route("deleteTakenExam",[$this->front]), $attributes);
        
        $response->assertStatus(403);
    }
    
    private function setupMocksAndAuth(){
        $this->be($this->user);

        $managerFactory = $this->createMock(FrontManagerFactory::class);
        $this->manager = $this->createMock(FrontManager::class);
        app()->instance(FrontManagerFactory::class, $managerFactory);
        $managerFactory->expects($this->any())
                ->method("getFrontManager")
                ->with($this->front->id)
                ->willReturn($this->manager);  
    }
    
}
