<?php

namespace Tests\Feature\Controllers;


use App\Models\TakenExam;
use App\Models\Front;
use App\Models\User;
use App\Models\Ssd;
use App\Domain\TakenExamDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    private $createAttributes;
    
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->front = Front::create(["user_id" => $this->user->id]);
        
        $ssd = Ssd::factory()->create();
        $this->createAttributes = [
            "name" => "exam 1",
            "cfu" => 5,
            "ssd" => $ssd->code,
            "grade" => 22
        ];
    }
    
    public function test_access_redirect_without_authentication(){
        $response = $this->post(route("postTakenExam",1), []);
        
        $response->assertRedirect(route("login"));
        
        $response = $this->delete(route("deleteTakenExam",1), []);
        
        $response->assertRedirect(route("login"));
    }
    
    public function test_create_successful(){
        $this->setupMocksAndAuth();
        $this-> manager->expects($this->once())
                ->method("saveTakenExam")
                ->with($this->createAttributes);

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $this->createAttributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
        
    }
    
    public function test_create_denied_by_policy_when_wrong_user(){
        $user2 = User::factory()->create();
        $this->setupMocksAndAuth();
        $this-> manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->actingAs($user2)
                ->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $this->createAttributes);
        
        $response->assertStatus(403);
        
    }
    
    public function test_create_validation_name_missing(){
        $this->createAttributes["name"] = null;
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $this->createAttributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_create_validation_cfu_missing(){
        $ssd = Ssd::factory()->create();
        $this->createAttributes["cfu"] = null;
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $this->createAttributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_create_validation_cfu_not_numeric(){
        $this->createAttributes["cfu"] = "5a";
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $this->createAttributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_create_validation_ssd_missing(){
        $this->createAttributes["ssd"] = null;
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $this->createAttributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_create_validation_ssd_not_existing(){
        $ssd = Ssd::factory()->create();
        $this->createAttributes["ssd"]++;
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $this->createAttributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_create_validation_grade_missing(){
        $this->createAttributes["grade"] = null;
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $this->createAttributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_create_validation_grade_not_numeric(){
        $this->createAttributes["grade"] = "6b";
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $response = $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $this->createAttributes);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_create_validation_grade_out_of_bounds(){
        $this->createAttributes["grade"] = 17;
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->never())
                ->method("saveTakenExam");

        $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $this->createAttributes)
                ->assertRedirect(self::FIXTURE_START_URI);
        
        $this->createAttributes["grade"] = 31;
        
        $this->from(self::FIXTURE_START_URI)
                ->post(route("postTakenExam",[$this->front]), $this->createAttributes)
                ->assertRedirect(self::FIXTURE_START_URI);
        
    }
    
    public function test_delete_success(){
        $exam = TakenExam::factory()->create([
            "ssd_id" => Ssd::factory()->create()
        ]);
        $attributes2 = [
            "exam" => serialize(new TakenExamDTO($exam->id,"test name","ssd",5,23))
        ];
        $this->setupMocksAndAuth();
        
        $this->manager->expects($this->once())
                ->method("deleteTakenExam")
                ->with($exam->id);

        $response = $this->from(self::FIXTURE_START_URI)
                ->delete(route("deleteTakenExam",[$this->front]), $attributes2);
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_delete_access_policy_with_no_authorized_user(){
        $user2 = User::factory()->create();
        $this->setupMocksAndAuth();
        
        $exam = TakenExam::factory()->create([
            "ssd_id" => Ssd::factory()->create()
        ]);
        $attributes2 = [
            "id" => $exam->id
        ];
        
        $this->manager->expects($this->never())
                ->method("deleteTakenExam");

        $response = $this->actingAs($user2)
                ->delete(route("deleteTakenExam",[$this->front]), $attributes2);
        
        $response->assertStatus(403);
    }
    
    public function test_deleteFromFront_success(){
        $this->setupMocksAndAuth();
        $this->manager->expects($this->once())
                ->method("deleteAllTakenExams");

        $response = $this->from(self::FIXTURE_START_URI)
                ->delete(route("deleteFrontTakenExam",[$this->front]));
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_deleteFromFront_access_policy_with_no_authorized_user(){
        $user2 = User::factory()->create();
        $this->setupMocksAndAuth();
                
        $this->manager->expects($this->never())
                ->method("deleteAllTakenExams");

        $response = $this->actingAs($user2)
                ->delete(route("deleteFrontTakenExam",[$this->front]));
        
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
