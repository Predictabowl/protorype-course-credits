<?php

namespace Tests\Feature\Repositories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Repositories\Implementations\UserRepositoryImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryImplTest extends TestCase {

    use RefreshDatabase;

    const FIXTURE_USER_NUM = 3;
    const FIXTURE_COURSE_NUM = 3;

    private UserRepositoryImpl $sut;

    protected function setUp(): void {
        parent::setUp();

        $this->sut = new UserRepositoryImpl();
    }

    public function test_get_successful() {
        $user = User::factory()->create();

        $found = $this->sut->get($user->id);

        $this->assertEquals($user->attributesToArray(), $found->attributesToArray());
        $this->assertTrue($found->relationLoaded("roles"));
    }

    public function test_get_fail() {

        $found = $this->sut->get(1);

        $this->assertNull($found);
    }

    public function test_save_successful() {
        $attributes = [
            "name" => "nome",
            "password" => "password",
            "email" => "posta",
        ];
        $user = new User($attributes);

        $result = $this->sut->save($user);
        
        $this->assertDatabaseHas("users", $attributes);
        $this->assertTrue($result);
    }

    public function test_save_with_id_not_null() {
        $user = new User([
            "name" => "nome",
            "password" => "password",
            "email" => "posta",
            "role" => "user"
        ]);
        $user-> id = 5;

        $this->expectException(\InvalidArgumentException::class);
        
        $this->sut->save($user);
        
        $this->assertDatabaseCount("users", 0);
    }
    
    public function test_delete_successful(){
        User::factory(2)->create();
        
        $result = $this->sut->delete(2);
        
        $this->assertEquals(1, $result);
        $this->assertDatabaseCount("users", 1);
        $this->assertDatabaseMissing("users", ["id" => 2]);
    }
    
    public function test_delete_failure(){
        User::factory(2)->create();
        
        $result = $this->sut->delete(3);
        
        $this->assertEquals(0, $result);
        $this->assertDatabaseCount("users", 2);
        $this->assertDatabaseHas("users", ["id" => 1]);
        $this->assertDatabaseHas("users", ["id" => 2]);
    }
    
    public function test_addRole_should_only_add_once(){
        User::factory()->create();
        Role::create(["name" => Role::ADMIN]);
        
        $result = $this->sut->addRole(1,Role::ADMIN);
        $this->assertTrue($result);
        
        $result = $this->sut->addRole(1,Role::ADMIN);
        $this->assertTrue($result);
        
        $this->assertDatabaseHas("role_user", [
            "role_id" => 1,
            "user_id" => 1
        ]);
        $this->assertDatabaseCount("role_user",1);
    }
    
    public function test_addRole_when_role_is_missing_should_fail(){
        User::factory()->create();
        
        $result = $this->sut->addRole(1,Role::ADMIN);
    
        $this->assertFalse($result);
        $this->assertDatabaseCount("role_user",0);
    }
     
    public function test_addRole_when_user_is_missing_should_fail(){
        Role::create(["name" => "admin"]);
        Log::shouldReceive("error")->once();
        
        $result = $this->sut->addRole(1,Role::ADMIN);
    
        $this->assertFalse($result);
        
        $this->assertDatabaseCount("role_user",0);
    }
    
    public function test_removeRole_when_relationship_is_not_present(){
        User::factory()->create();
        Role::create(["name" => Role::SUPERVISOR]);
        
        $result = $this->sut->removeRole(1,Role::SUPERVISOR);
        
        $this->assertTrue($result);
        $this->assertDatabaseCount("role_user",0);
    }
    
    public function test_removeRole_when_relationship_is_present(){
        $user = User::factory()->create();
        $role = Role::create(["name" => Role::ADMIN]);
        $user->roles()->attach($role);
        $role = Role::create(["name" => Role::SUPERVISOR]);
        $user->roles()->attach($role);
        
        $result = $this->sut->removeRole(1,Role::SUPERVISOR);
        
        $this->assertTrue($result);
        $this->assertDatabaseCount("role_user",1);
        $this->assertDatabaseHas("role_user", [
            "user_id" => 1,
            "role_id" => 1
        ]);
        
        $this->assertDatabaseMissing("role_user", [
            "user_id" => 1,
            "role_id" => 2
        ]);
    }
    
    public function test_removeRole_when_user_is_not_present(){
        Role::create(["name" => Role::SUPERVISOR]);
        Log::shouldReceive("error")->once();
        
        $result = $this->sut->removeRole(1,Role::SUPERVISOR);
        
        $this->assertFalse($result);
        $this->assertDatabaseCount("role_user",0);
    }
    
    public function test_removeRole_when_role_is_not_present(){
        User::factory()->create();
        Role::create(["name" => Role::SUPERVISOR]);
        
        $result = $this->sut->removeRole(1,Role::ADMIN);
        
        $this->assertTrue($result);
        $this->assertDatabaseCount("role_user",0);
    }
    
    public function test_getAll_wtih_no_users() {
        
        $result = $this->sut->getAll([]);
        
        $this->assertEmpty($result);
    }
    
    public function test_getAll_no_filters(){
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $role = Role::create(["name" => Role::SUPERVISOR]);
        $user1->roles()->attach($role);
        $user2->roles()->attach($role);
        
        $result = $this->sut->getAll([]);
        
        $this->assertCount(2, $result);
        $this->assertEquals(User::with("roles")->first(), $result[0]);
        $this->assertEquals(User::with("roles")->find(2), $result[1]);
    }
    
    public function test_getAll_search_name_filters(){
        $user1 = User::factory()->create([
            "name" => "mario",
            "email" => "email1"
        ]);
        $user2 = User::factory()->create([
            "name" => "luigi",
            "email" => "email2"
        ]);
        $role = Role::create(["name" => Role::SUPERVISOR]);
        $user1->roles()->attach($role);
        $user2->roles()->attach($role);
        
        $result = $this->sut->getAll(["search" => "mar"]);
        
        $this->assertCount(1, $result);
        $this->assertEquals(User::with("roles")->first(), $result[0]);
    }
    
    public function test_getAll_search_email_filters(){
        $user1 = User::factory()->create([
            "name" => "mario",
            "email" => "email1"
        ]);
        $user2 = User::factory()->create([
            "name" => "luigi",
            "email" => "email2"
        ]);
        $role = Role::create(["name" => Role::SUPERVISOR]);
        $user1->roles()->attach($role);
        $user2->roles()->attach($role);
        
        $result = $this->sut->getAll(["search" => "email2"]);
        
        $this->assertCount(1, $result);
        $this->assertEquals(User::with("roles")->find(2), $result[0]);
    }
    
    public function test_update_successful() {
        $user = User::factory()->create([
            "name" => "old name"
        ]);
        $user->name = "new name";

        $result = $this->sut->update($user);
        
        $this->assertDatabaseCount("users", 1);
        $this->assertDatabaseHas("users", ["name" => "new name"]);
        $this->assertDatabaseMissing("users", ["name" => "old name"]);
        $this->assertTrue($result);
    }

    public function test_update_with_id_null_should_throw() {
        $user = new User([
            "name" => "nome",
            "password" => "password",
            "email" => "posta",
            "role" => "user"
        ]);

        $this->expectException(\InvalidArgumentException::class);
        
        $result = $this->sut->update($user);
        
        $this->assertDatabaseCount("users", 0);
        $this->assertFalse($result);
    }
    
    public function test_update_non_existing_model_not_save() {
        $user = new User([
            "name" => "nome",
            "password" => "password",
            "email" => "posta",
            "role" => "user"
        ]);
        $user->id = 5;

        $result = $this->sut->update($user);
        
        $this->assertDatabaseCount("users", 0);
        $this->assertFalse($result);
    }
    
    public function test_getByRoles_whenNoRoleFound(){
        User::factory(3)->create();
        
        $result = $this->sut->getByRole("norole");
        
        $this->assertEmpty($result->toArray());
    }
    
    public function test_getByRole(){
        $user1 = User::factory()->create();
        User::factory()->create();
        $user3 = User::factory()->create();
        $role1 = Role::create(["name" => "a role"]);
        $user1->roles()->attach($role1);
        $user1->save();
        $user3->roles()->attach($role1);
        $user3->save();
        
        $result = $this->sut->getByRole("a role");
        
        $this->assertCount(2, $result->toArray());
    }
}