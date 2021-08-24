<?php

namespace Tests\Feature\Controllers;


use App\Models\Front;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\UserFrontManager;

use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;
    
    
    public function test_authentication_required(){
        $response =  $this->get(route("frontPersonal"));
        
        $response->assertRedirect(route("login"));
    }
    
    public function test_showFront_should_redirect_to_front_controller(){
        $user =  User::factory()->create();
        $front = Front::create(["user_id" => $user->id]);
        $frontManager = $this->createMock(FrontManager::class);
        $userFrontManager = $this->createMock(UserFrontManager::class);
        app()->instance(UserFrontManager::class, $userFrontManager);
        
        $userFrontManager->expects($this->once())
                ->method("getFrontManager")
                ->willReturn($frontManager);
        
        $frontManager->expects($this->once())
                ->method("getFront")
                ->willReturn($front);
        
        $response = $this->actingAs($user)
                ->get(route("frontPersonal"));
        
        $response->assertRedirect(route("frontView",[$front]));
    }
    
}
