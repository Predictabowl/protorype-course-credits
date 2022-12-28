<?php

namespace Tests\Feature\Controllers;

use App\Models\Course;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use App\Services\Interfaces\CourseAdminManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use function route;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;
    
    const FIXTURE_START_URI = "course";
    
    private CourseAdminManager $courseManager;
    private User $user;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->course = Course::factory()->create();
        
        $this->courseManager = $this->createMock(CourseAdminManager::class);
    }
    
    public function test_index_authorization_forbidden(){
        $this->be($this->user);
        
        $response = $this->get(route("courseIndex"));
        $response->assertStatus(403);
    }
    
    public function test_index_auth_admin_success(){
        $roleAdmin = Role::create([
            "name" => "admin"
        ]);
        $admin = User::factory()->create([
            "name" => "Amministratore Temporaneo",
            "email" => "admin@email.org",
            "password" => Hash::make("password")
        ]);
        RoleUser::create([
            "user_id" => $admin->id,
            "role_id" => $roleAdmin->id
        ]);
        $this->be($admin);
        
        //$response = $this->get(route("courseIndex"));
        //$response->assertStatus(200);
    }
}
