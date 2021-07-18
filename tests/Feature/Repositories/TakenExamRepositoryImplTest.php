<?php

namespace Tests\Feature\Repositories;


use App\Repositories\Implementations\TakenExamRespositoryImpl;
use App\Domain\TakenExamDTO;
use App\Models\TakenExam;
use App\Models\Ssd;
use App\Models\User;
use App\Models\Course;
use App\Models\Front;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class TakenExamRepositoryImplTest extends TestCase
{
    use RefreshDatabase;
    
    private $repository;
    
    protected function setUp(): void {
        parent::setUp();
        $this->repository = new TakenExamRespositoryImpl();
    }
    
    
    public function test_get_when_not_present(){
        $sut = $this->repository->get(1);
        
        $this->assertNull($sut);
    }
   
    public function test_get_successful()
    {
        $taken = TakenExam::factory()->create([
            "ssd_id" => Ssd::factory()->create(),
            "front_id" => Front::factory()->create([
               "user_id" => User::factory()->create(),
                "course_id" => Course::factory()->create()
            ])
        ]);
        
        $sut = $this->repository->get(1);
        
        $this->assertEquals(
                [$taken->id, $taken->ssd->code, $taken->cfu, $taken->name],
                [$sut->getId(), $sut->getSsd(), $sut->getCfu(), $sut->getExamName()]);
        
    }
    
    public function test_getAll_when_empty()
    {
        Ssd::factory(3)->create();
        $front = Front::factory()->create([
               "user_id" => User::factory()->create(),
                "course_id" => Course::factory()->create()
            ]);
        
        $sut = $this->repository->getFromFront($front->id);
        
        $this->assertEmpty($sut);
    }
    
    public function test_getFrontFront_success()
    {
        Ssd::factory(3)->create();
        $front = Front::factory()->create([
               "user_id" => User::factory()->create(),
                "course_id" => Course::factory()->create()
            ]);
        
        TakenExam::factory(3)->create([
            "front_id" => $front
        ]);
        
        $sut = $this->repository->getFromFront($front->id);
        
        $this->assertCount(3, $sut);
        $this->assertContainsOnlyInstancesOf(TakenExamDTO::class, $sut);
    }
    
    public function test_getFrontFront_when_front_not_present()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository->getFromFront(1);
    }
    
    public function test_getFrontFront_when_front_is_empty()
    {
        $front = Front::factory()->create([
               "user_id" => User::factory()->create(),
               "course_id" => Course::factory()->create()
            ]);
        
        $sut = $this->repository->getFromFront($front->id);
        
        $this->assertEmpty($sut);
    }
    
    public function test_save_success(){
        $ssd = Ssd::factory()->create();
        $front = Front::factory()->create([
               "user_id" => User::factory()->create(),
                "course_id" => Course::factory()->create()
            ]);
        $exam = new TakenExamDTO(0, "test name", $ssd->code, 6);
        
        
        $this->repository->save($exam, $front->id);
        
        $this->assertDatabaseHas("taken_exams", [
            "front_id" => $front->id,
            "ssd_id" => $ssd->id,
            "name" => "test name",
            "cfu" => 6
        ]);
    }
    
    public function test_delete_success() {
        $ssd = Ssd::factory()->create();
        $front = Front::factory()->create([
               "user_id" => User::factory()->create(),
                "course_id" => Course::factory()->create()
            ]);
        TakenExam::factory(2)->create();
        $taken = [
            "id" => 5,
            "name" => "Mario",
            "cfu" => 7,
            "ssd_id" => $ssd->id,
            "front_id" => $front->id,
        ];
        TakenExam::factory()->create($taken);
        
        $this->assertDatabaseHas("taken_exams", $taken);
        
        $this->repository->delete(5);
        
        $this->assertDatabaseCount("taken_exams", 2);
        $this->assertDatabaseMissing("taken_exams", $taken);
    }

}
