<?php

namespace Tests\Feature\Repositories;

use App\Models\Course;
use App\Models\Front;
use App\Models\User;
use App\Repositories\Implementations\FrontRepositoryImpl;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Tests\TestCase;

class FrontRepositoryImplTest extends TestCase
{
    use RefreshDatabase;
    
    const FIXTURE_USER_NUM = 3;
    const FIXTURE_COURSE_NUM = 3;


    private FrontRepositoryImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        
        $this->sut = new FrontRepositoryImpl();
    }
    
    private function populateData(){
        Course::factory(self::FIXTURE_COURSE_NUM)->create();
        User::factory(self::FIXTURE_USER_NUM)->create();
    }
    
    public function test_save_successful()
    {
        $this->populateData();
        $front = new Front([
            "user_id" => 3,
            "course_id" => 2
        ]);
        
        $result = $this->sut->save($front);
        
        $this->assertInstanceOf(Front::class, $result);
        $this->assertEquals(1, $result->id);
        
        $this->assertDatabaseHas("fronts", [
            "id" => 1,
            "course_id" => 2,
            "user_id" => 3
        ]);
    }

    public function test_save_when_id_not_null()
    {
        $new = new Front([
            "user_id" => 2
        ]);
        $new->id = 3;
        $this->expectException(InvalidArgumentException::class);
        
        $result = $this->sut->save($new);
        
        $this->assertFalse($result);
        $this->assertDatabaseCount("fronts", 0);
    }
    
    public function test_save_when_course_id_not_exists()
    {
        $new = new Front([
            "user_id" => 2,
            "course_id" => self::FIXTURE_COURSE_NUM+1
        ]);
        Log::shouldReceive("error")->once();
        
        $result = $this->sut->save($new);
        
        $this->assertNull($result);
        $this->assertDatabaseCount("fronts", 0);
    }

    
    public function test_save_when_user_id_already_present()
    {
        $this->populateData();
        Front::create([
            "course_id" => 3,
            "user_id" => 2
        ]);
        
        $new = new Front([
            "course_id" => 1,
            "user_id" => 2
        ]);
        
        $result = $this->sut->save($new);
        
        $this->assertNull($result);
        
        $this->assertDatabaseCount("fronts", 1);
        $this->assertDatabaseMissing("fronts", [
            "course_id" => 1,
            "user_id" => 2
        ]);
    }

    
    public function test_delete_when_not_present(){
        $this->populateData();
        Front::factory()->create();
        
        $result = $this->sut->delete(2);
        
        $this->assertEquals(0,$result);
        $this->assertDatabaseCount("fronts", 1);
    }
    
    public function test_delete_when_present(){
        $this->populateData();
        Front::create([
            "id" => 1,
            "course_id" => 2,
            "user_id" => 3
        ]);
        $frontArray = [
            "id" => 2,
            "course_id" => 2,
            "user_id" => 1
        ];
        Front::create($frontArray);
        
        $result = $this->sut->delete(1);
        
        $this->assertEquals(1, $result);
        $this->assertEquals([2,2,1], array_values($frontArray));
        $this->assertDatabaseCount("fronts", 1);
        $this->assertDatabaseMissing("fronts", array_values($frontArray));
    }
    
    public function test_get_when_front_not_persent() {
        $sut = $this->sut->get(1);
        
        $this->assertNull($sut);
    }
    
    public function test_get_success() {
        $this->populateData();
        $frontArray = [
            "id" => 5,
            "course_id" => 3,
            "user_id" => 2
        ];
        Front::factory()->create($frontArray);
        
        $sut = $this->sut->get(5);
        
        $this->assertEquals(array_values($frontArray),
                [$sut["id"], $sut["course_id"], $sut["user_id"]]);
    }
    
    public function test_getFromUser_when_User_not_present() {
        $this->populateData();
        $this->expectException(ModelNotFoundException::class);

        $this->sut->getFromUser(self::FIXTURE_USER_NUM+1);
    }
    
    public function test_getFromUser_when_front_not_present() {
        $this->populateData();
        $sut = $this->sut->getFromUser(1);
        
        $this->assertEmpty($sut);
    }
    
    public function test_getFromUser_success() {
        $this->populateData();
        $frontArray = [
            "id" => 1,
            "course_id" => 2,
            "user_id" => 3
        ];
        Front::factory()->create($frontArray);
         
        $sut = $this->sut->getFromUser(3);
        
        $this->assertInstanceOf(Front::class, $sut);
        $this->assertEquals(array_values($frontArray),
                [$sut["id"],$sut["course_id"],$sut["user_id"]]);
    }
    
    public function test_updateCourse_when_Front_not_present(){
        $sut = $this->sut->updateCourse(1,1);
        
        $this->assertNull($sut);
        $this->assertDatabaseCount("fronts", 0);
    }
    
    public function test_updateCourse_when_Course_not_present(){
        $this->populateData();
        $frontArray = [
            "id" => 1,
            "course_id" => 3,
            "user_id" => 3
        ];
        Front::factory()->create($frontArray);
        Log::shouldReceive("error")->once();
        
        $result = $this->sut->updateCourse(1,self::FIXTURE_COURSE_NUM+1);
        
        $this->assertNull($result);
        $this->assertDatabaseMissing("fronts", ["course_id" => self::FIXTURE_COURSE_NUM+1]);
    }
    
    public function test_updateCourse_succesful(){
        $this->populateData();
        $changedFront = [
            "id" => 1,
            "course_id" => 2,
            "user_id" => 3
        ];
        Front::factory()->create([
            "course_id" => 3,
            "user_id" => 3
        ]);
        
        $sut = $this->sut->updateCourse(1,2);
        
        $this->assertInstanceOf(Front::class,$sut);
        $this->assertEquals([1,2,3],
                [$sut["id"],$sut["course_id"],$sut["user_id"]]);
        $this->assertDatabaseHas("fronts", $changedFront);
    }
    
    public function test_getAll_no_filters(){
        Front::create(["user_id" => User::factory()->create()->id]);
        Front::create(["user_id" => User::factory()->create()->id]);
        Front::create(["user_id" => User::factory()->create()->id]);
                
        $result = $this->sut->getAll([],25);

        $this->assertCount(3, $result);
        $this->assertEquals(Front::with("user","course")->paginate(25), $result);
    }
    
    public function test_getAll_search_user_filter(){
        Front::create([
            "user_id" => User::factory()->create([
                "name" => "mario",
                "email" => "testmail"
            ])->id
        ]);
        Front::create([
            "user_id" => User::factory()->create([
                "name" => "giulio",
                "email" => "giulio@posta.it"
            ])->id
        ]);
        Front::create([
            "user_id" => User::factory()->create([
                "name"  => "test 1",
                "email" => "mariangela@email.com"
            ])->id
        ]);
                
        $result = $this->sut->getAll(["search" => "mar"]);
        
        $this->assertCount(2, $result);
        $this->assertEquals(Front::with("user","course")->first(), $result[0]);
        $this->assertEquals(Front::with("user","course")->find(3), $result[1]);
    }
    
    public function test_getAll_search_course_filter(){
        Front::factory()->create([
            "course_id" => Course::factory()->create(["name" => "Corso di qualcosa"])
        ]);
        Front::factory()->create(["course_id" => null]);
        Front::factory()->create([
            "course_id" => Course::factory()->create(["name" => "Corso di qualcosaltro"])
        ]);
                
        $result = $this->sut->getAll(["course" => 1]);
        

        
        $this->assertCount(1, $result);
        $this->assertEquals(Front::with("user","course")->first(), $result[0]);
    }
    
    public function test_getAll_search_both_filters(){
        $courseName = "Corso tangenziale";
        $course = Course::factory()->create(["name" => $courseName]);
        
        Front::factory()->create([
            "user_id" => User::factory()->create(["name" => "carlo"]),
            "course_id" => Course::factory()->create(["name" => "Corso di qualcosa"])
        ]);
        Front::factory()->create([
            "user_id" => User::factory()->create(["name" => "luigi"]),
            "course_id" => $course->id
        ]);
        $front = Front::factory()->create([
            "user_id" => User::factory()->create(["name" => "carlo"]),
            "course_id" => $course->id
        ]);
                
        $result = $this->sut->getAll([
            "search" => "arl",
            "course" => $course->id]);
        
        $this->assertCount(1, $result);
        $actual = Front::with("user","course")->find($front->id);
        $this->assertEquals($actual, $result[0]);
    }
}
