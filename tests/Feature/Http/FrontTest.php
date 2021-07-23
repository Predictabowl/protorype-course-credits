<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use \Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Front;
use Tests\TestCase;

class FrontTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_front_access_with_basic_authentication()
    {
        $user = User::factory()->create();
        $role = \App\Models\Role::create(["name" => "admin"]);
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
