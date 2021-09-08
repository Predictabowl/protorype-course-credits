<?php

namespace Tests\Feature\Controllers;


use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Interfaces\UserManager;

use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    
    const FIXTURE_START_URI = "userURI";
    
    private $user;
    private $manager;
    
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->be($this->user);
        $this->user->roles()->attach(Role::create(["name" => Role::ADMIN]));
        $this->manager = $this->createMock(UserManager::class);
        app()->instance(UserManager::class, $this->manager);
    }
      
    public function test_authorization_routes(){
        $user2 = User::factory()->create();
        
        $this->actingAs($user2)
            ->get(route("userIndex"))
            ->assertForbidden();
        
        $this->actingAs($user2)
            ->delete(route("userDelete",[$this->user]))
            ->assertForbidden();
        
        $this->actingAs($user2)
            ->get(route("userShow",[$user2]))
            ->assertForbidden();
        
        $this->actingAs($user2)
            ->get(route("userUpdate",[$this->user]))
            ->assertForbidden();
        
        $this->actingAs($user2)
            ->put(route("userUpdate",[$this->user]))
            ->assertForbidden();
    }
    
    public function test_index_with_filters_success(){
        $filters = ["search" => "something"];
        User::factory()->create();
        User::factory()->create();
        $users = User::paginate();
        
        $this->manager->expects($this->once())
                ->method("getAll")
                ->with($filters)
                ->willReturn($users);
        
        $response = $this->get(route("userIndex",$filters));
        
        $response->assertOk()->assertViewHas(["users" => $users]);
    }
    
    public function test_updateView(){
        $user2 = User::factory()->create();
        
        $response = $this->actingAs($user2)
                ->get(route("userUpdate",$user2));
        
        $response->assertOk()->assertViewHas(["user" => $user2]);
    }
    
    public function test_put_success(){
        $user2 = User::factory()->create();
        $newName = "new name";
        
        $this->manager->expects($this->once())
                ->method("setName")
                ->with($user2->id,$newName);
        
        $response = $this->actingAs($user2)
                ->put(route("userUpdate",[$user2]),["name" => $newName]);
        
        $response->assertRedirect(route("dashboard"));
    }
    
    public function test_put_validation_failure(){
        $user2 = User::factory()->create();
        
        $this->manager->expects($this->never())
                ->method("setName");
        
        $response = $this->actingAs($user2)
                ->from(self::FIXTURE_START_URI)
                ->put(route("userUpdate",[$user2]));
        
        $response->assertRedirect(self::FIXTURE_START_URI);
    }
    
    public function test_show(){
        $user2 = User::factory()->create();
        
        $this->get(route("userShow",$user2))
                ->assertOk()
                ->assertViewHas(["user" => $user2]);
    }
    
    public function test_delete_successful(){
        $user2 = User::factory()->create();
        
        $this->manager->expects($this->once())
                ->method("deleteUser")
                ->with($user2->id)
                ->willReturn(true);
        
        $this->from(self::FIXTURE_START_URI)
                ->delete(route("userDelete",$user2))
                ->assertRedirect(self::FIXTURE_START_URI)
                ->assertSessionHas(["success" => "Eliminato utente: ".$user2->name]);
    }
    
    public function test_delete_failure(){
        $user2 = User::factory()->create();
        
        $this->manager->expects($this->never())
                ->method("deleteUser");
        User::destroy($user2->id);
        
        $this->from(self::FIXTURE_START_URI)
                ->delete(route("userDelete",$user2))
                ->assertNotFound();
    }
    
}
