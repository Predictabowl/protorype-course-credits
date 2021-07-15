<?php

namespace Tests\Feature\Repositories;


use App\Repositories\Implementations\TakenExamRespositoryImpl;
use App\Domain\TakenExamDTO;
use App\Models\TakenExam;
use App\Models\Ssd;
use App\Models\User;
use App\Models\Course;
use App\Models\Front;
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
    
   
    public function test_get()
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
    
    public function test_getAll()
    {
        Ssd::factory(3)->create();
        $front = Front::factory()->create([
               "user_id" => User::factory()->create(),
                "course_id" => Course::factory()->create()
            ]);
        
        $taken = TakenExam::factory(3)->create([
            "front_id" => $front
        ]);
        
        $sut = $this->repository->getAll($front->id);
        
        $this->assertCount(3, $sut);
        $this->assertContainsOnlyInstancesOf(TakenExamDTO::class, $sut);
    }
    

}
