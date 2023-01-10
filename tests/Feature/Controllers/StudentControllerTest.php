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
    
    
    private UserFrontManager $userFrontManager;
    private UserFrontManagerFactory $ufManagerFactory;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this-> userFrontManager = $this->createMock(UserFrontManager::class);
        $this->ufManagerFactory = $this->createMock(UserFrontManagerFactory::class);
        
        app()->instance(UserFrontManagerFactory::class, $this-> ufManagerFactory);
    }

    
    public function test_authentication_required(){
        $response =  $this->get(route("frontPersonal"));
        
        $response->assertRedirect(route("login"));
    }
    
    public function test_showFront_should_redirect_to_front_controller(){
        $user =  User::factory()->create();
        $front = Front::create(["user_id" => $user->id]);
        $frontManager = $this->createMock(FrontManager::class);
        
        $this->ufManagerFactory->expects($this->once())
                ->method("get")
                ->with($user->id)
                ->willReturn($this->userFrontManager);
        
        $this->userFrontManager->expects($this->once())
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
