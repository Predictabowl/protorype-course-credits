<?php

namespace Tests\Feature\Repositories;

use App\Models\User;
use App\Repositories\Implementations\UserRepositoryImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryImplTest extends TestCase {

    use RefreshDatabase;

    const FIXTURE_USER_NUM = 3;
    const FIXTURE_COURSE_NUM = 3;

    private $repository;

    protected function setUp(): void {
        parent::setUp();

        $this->repository = new UserRepositoryImpl();
    }

    public function test_get_successful() {
        $user = User::factory()->create();

        $found = $this->repository->get($user->id);

        $this->assertEquals($user->attributesToArray(), $found->attributesToArray());
    }

    public function test_get_fail() {

        $found = $this->repository->get(1);

        $this->assertNull($found);
    }

    public function test_save_successful() {
        $attributes = [
            "name" => "nome",
            "password" => "password",
            "email" => "posta",
        ];
        $user = new User($attributes);

        $result = $this->repository->save($user);
        
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
        
        $this->repository->save($user);
        
        $this->assertDatabaseCount("users", 0);
    }
    
    public function test_delete_successful(){
        User::factory(2)->create();
        
        $result = $this->repository->delete(2);
        
        $this->assertEquals(1, $result);
        $this->assertDatabaseCount("users", 1);
        $this->assertDatabaseMissing("users", ["id" => 2]);
    }
    
    public function test_delete_failure(){
        User::factory(2)->create();
        
        $result = $this->repository->delete(3);
        
        $this->assertEquals(0, $result);
        $this->assertDatabaseCount("users", 2);
        $this->assertDatabaseHas("users", ["id" => 1]);
        $this->assertDatabaseHas("users", ["id" => 2]);
    }

}
