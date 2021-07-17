<?php

namespace Tests\Feature\Repositories;

use App\Models\Front;
use App\Models\User;
use App\Models\Course;
use App\Repositories\Implementations\FrontRepositoryImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FrontRepositoryImplTest extends TestCase
{
    use RefreshDatabase;
    
    const FIXTURE_USER_NUM = 3;
    const FIXTURE_COURSE_NUM = 3;


    private $repository;
    
    protected function setUp(): void {
        parent::setUp();
        Course::factory(self::FIXTURE_COURSE_NUM)->create();
        User::factory(self::FIXTURE_USER_NUM)->create();
        
        $this->repository = new FrontRepositoryImpl();
    }
    
    public function test_save_when_not_present()
    {
        $front = $this->repository->save(2,3);
        
        $this->assertInstanceOf(Front::class, $front);
        
        $this->assertEquals([1,2,3],
                [$front["id"], $front["course_id"], $front["user_id"]]);
        $this->assertDatabaseHas("fronts", [
            "id" => 1,
            "course_id" => 2,
            "user_id" => 3
        ]);
    }
    
    public function test_save_when_already_present()
    {
        Front::create([
            "course_id" => 3,
            "user_id" => 2
        ]);
        
        $front = $this->repository->save(3,2);
        
        $this->assertInstanceOf(Front::class, $front);
        
        $this->assertEquals([1,3,2],
                [$front["id"], $front["course_id"], $front["user_id"]]);
        $this->assertDatabaseCount("fronts", 1);
        $this->assertDatabaseHas("fronts", [
            "id" => 1,
            "course_id" => 3,
            "user_id" => 2
        ]);
    }
    
    public function test_save_when_same_user_but_different_course_is_present(){
        Front::create([
            "course_id" => 3,
            "user_id" => 2
        ]);
        
        $front = $this->repository->save(2,2);
        
        $this->assertNull($front);
        $this->assertDatabaseCount("fronts", 1);
        $this->assertDatabaseMissing("fronts", [
            "id" => 1,
            "course_id" => 2,
            "user_id" => 2
        ]);
    }
    
    public function test_delete_when_not_present(){
        Front::factory()->create();
        
        $result = $this->repository->delete(2);
        
        $this->assertEquals(0,$result);
        $this->assertDatabaseCount("fronts", 1);
    }
    
    public function test_delete_when_present(){
        Front::create([
            "id" => 1,
            "course_id" => 2,
            "user_id" => 1
        ]);
        $frontArray = [
            "id" => 2,
            "course_id" => 2,
            "user_id" => 1
        ];
        Front::create($frontArray);
        
        $result = $this->repository->delete(1);
        
        $this->assertEquals(1, $result);
        $this->assertEquals([2,2,1], array_values($frontArray));
        $this->assertDatabaseCount("fronts", 1);
        $this->assertDatabaseMissing("fronts", array_values($frontArray));
    }
    
    public function test_get_when_front_not_persent() {
        $sut = $this->repository->get(1);
        
        $this->assertNull($sut);
    }
    
    public function test_get_success() {
        $frontArray = [
            "id" => 5,
            "course_id" => 3,
            "user_id" => 2
        ];
        Front::factory()->create($frontArray);
        
        $sut = $this->repository->get(5);
        
        $this->assertEquals(array_values($frontArray),
                [$sut["id"], $sut["course_id"], $sut["user_id"]]);
    }
    
    public function test_getFromUser_when_User_not_present() {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->getFromUser(self::FIXTURE_USER_NUM+1);
    }
    
    public function test_getFromUser_when_front_not_present() {
        $sut = $this->repository->getFromUser(1);
        
        $this->assertEmpty($sut);
    }
    
    public function test_getFromUser_success() {
        $frontArray = [
            "id" => 1,
            "course_id" => 2,
            "user_id" => 3
        ];
        Front::factory()->create($frontArray);
         
        $sut = $this->repository->getFromUser(3);
        
        $this->assertInstanceOf(Front::class, $sut);
        $this->assertEquals(array_values($frontArray),
                [$sut["id"],$sut["course_id"],$sut["user_id"]]);
    }
    
    public function test_updateCourse_when_Front_not_present(){
        $sut = $this->repository->updateCourse(1,1);
        
        $this->assertNull($sut);
    }
    
    public function test_updateCourse_when_Course_not_present(){
        $frontArray = [
            "id" => 1,
            "course_id" => 3,
            "user_id" => 3
        ];
        Front::factory()->create($frontArray);
        
        $this->expectException(ModelNotFoundException::class);
        
        $this->repository->updateCourse(1,self::FIXTURE_COURSE_NUM+1);
    }
    
    public function test_updateCourse_succesful(){
        $frontArray = [
            "id" => 1,
            "course_id" => 3,
            "user_id" => 3
        ];
        Front::factory()->create($frontArray);
        
        $sut = $this->repository->updateCourse(1,2);
        
        $this->assertInstanceOf(Front::class,$sut);
        $this->assertEquals([1,2,3],
                [$sut["id"],$sut["course_id"],$sut["user_id"]]);
    }
}
