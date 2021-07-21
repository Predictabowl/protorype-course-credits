<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Description of UserTest
 *
 * @author piero
 */
class UserTest extends TestCase{
    
    use RefreshDatabase;
    
    public function test_hasAtLeastOneRole_success() {
        $role = Role::create(["name" => Role::SUPERVISOR]);
        $user = User::factory()->create();
        $user->roles()->attach($role);
        
        $result = $user->hasAtLeastOneRole(Role::ADMIN, Role::SUPERVISOR);
        
        $this->assertTrue($result);
    }
    
    public function test_hasAtLeastOneRole_failure() {
        $user = User::factory()->create();
        
        $result = $user->hasAtLeastOneRole(Role::ADMIN, Role::SUPERVISOR);
        
        $this->assertFalse($result);
    }
    
}
