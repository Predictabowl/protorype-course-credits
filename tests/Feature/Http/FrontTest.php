<?php

namespace Tests\Feature\Http;

use App\Models\Front;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use function route;

class FrontTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_front_access_with_basic_authentication()
    {
        $user = User::factory()->create();
        $role = Role::create(["name" => Role::ADMIN]);
        $user->roles()->attach($role);
        $front = Front::create(["user_id" => $user->id]);
        $user = User::first();
        $this->assertEquals(1,$user->id);
        $this->assertEquals(1,$front->user_id);
        
//        $policy = policy(Front::class);
        
        $response = $this->actingAs($user)->get(route("frontView",[$front]));

//        $r = $policy->viewAny($user);
//        dd($r);
        //$response = $this->get(route("frontView",[$front]));
        
//        $response->assertStatus(200);
    }
    
}
