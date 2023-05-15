<?php

namespace Tests\Feature\Controllers;

use App\Factories\Interfaces\UserFrontManagerFactory;
use App\Models\Front;
use App\Models\User;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\UserFrontManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function app;
use function route;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;
    
    
    private FrontManager $frontManager;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this-> frontManager = $this->createMock(FrontManager::class);
        app()->instance(FrontManager::class, $this-> frontManager);
    }

    
    public function test_authentication_required(){
        $response =  $this->get(route("frontPersonal"));
        
        $response->assertRedirect(route("login"));
    }
    
    public function test_showFront_should_redirect_to_front_controller(){
        $user =  User::factory()->create();
        $front = Front::create(["user_id" => $user->id]);
        
        $this->frontManager->expects($this->once())
                ->method("getOrCreateFront")
                ->with($user->id)
                ->willReturn($front);
        
        $response = $this->actingAs($user)
                ->get(route("frontPersonal"));
        
        $response->assertRedirect(route("frontView",[$front]));
    }
    
}
