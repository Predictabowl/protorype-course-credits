<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_dashboard_with_no_authentication()
    {
        $response = $this->get(route("dashboard"));
        $response->assertRedirect("/login");
    }
    
    public function test_dashboard_with_authentication()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route("dashboard"));
        
        $response->assertStatus(200);
    }
    
}
