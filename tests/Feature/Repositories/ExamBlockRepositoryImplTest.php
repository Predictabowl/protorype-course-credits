<?php

namespace Tests\Feature\Repositories;


use App\Repositories\Implementations\ExamBlockRepositoryImpl;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\Course;
use App\Models\Ssd;
use App\Models\Exam;
use App\Models\Front;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Tests\TestCase;

class ExamBlockRepositoryImplTest extends TestCase
{
    use RefreshDatabase;
    
    private $repository;
    
    protected function setUp(): void {
        parent::setUp();
        $this->repository = new ExamBlockRepositoryImpl();
    }
    
    public function test_get_when_not_present(){
        $sut = $this->repository->get(2);
        
        $this->assertNull($sut);
    }
  
    /*
     * Thios doesn't actually test the eager load, so the added options are
     * bloat.
     */
    public function test_get_block_with_options()
    {
        $course = Course::factory()->create();
        $ssds = Ssd::factory(3)->create();
        
        $block = ExamBlock::factory()->create([
            "course_id" => $course
        ]);
        
        $option = ExamBlockOption::factory()->create([
            "exam_id" => Exam::factory()->create(),
            "exam_block_id" => $block
        ]);
        $option->ssds()->attach($ssds);
        
        $sut = $this->repository->get(1);
        
        $block = ExamBlock::find(1);
        $this->assertEquals($block->attributesToArray(), $sut->attributesToArray());
    }
    
    public function test_getFromFront_when_front_not_present() {
        $this->expectException(ModelNotFoundException::class);
        
        $this->repository->getFromFront(3);
    }

    public function test_getFromFront_when_front_is_empty() {
        Front::factory()->create([
           "course_id" => Course::factory()->create(),
           "user_id" => User::factory()->create()
        ]);
        
        $sut = $this->repository->getFromFront(1);
        
        $this->assertEmpty($sut);
    }
    
    public function test_getFromFront_when_course_is_not_set() {
        Front::factory()->create([
            "course_id" => null,
           "user_id" => User::factory()->create()
        ]);
        
        $result = $this->repository->getFromFront(1);
        
        $this->assertEmpty($result);
    }


    public function test_getFromFront_without_options(){
        $course = Course::factory()->create();
        User::factory()->create();
        $front = Front::factory()->create([
            "course_id" => $course
        ]);
        
        ExamBlock::factory(3)->create([
            "course_id" => $course
        ]);
        
        $result = $this->repository->getFromFront($front->id);
        
        $this->assertCount(3,$result);
        $this->assertContainsOnlyInstancesOf(ExamBlock::class, $result);
        // this test was cut short to save time
    }
    
    public function test_getFromFront_with_options(){
        $course = Course::factory()->create();
        User::factory()->create();
        $front = Front::factory()->create([
            "course_id" => $course
        ]);
        Ssd::factory(4)->create();
        
        $blocks = ExamBlock::factory(3)->create([
            "course_id" => $course
        ]);
        
        ExamBlockOption::factory(3)->create([
            "exam_id" => Exam::factory()->create(),
            "exam_block_id" => $blocks[0]
        ]);
        
        ExamBlockOption::factory(2)->create([
            "exam_id" => Exam::factory()->create(),
            "exam_block_id" => $blocks[1]
        ]);
        
        ExamBlockOption::factory(1)->create([
            "exam_id" => Exam::factory()->create(),
            "exam_block_id" => $blocks[2]
        ]);
        
        $result = $this->repository->getFromFront($front->id);
        
        $this->assertCount(3,$result);
        $this->assertContainsOnlyInstancesOf(ExamBlock::class, $result);
        //$this->assertCount(2,$result[1]->getExamOptions());
        //$this->assertCount(1,$result[2]->getExamOptions());
        
        //This test was cut short as well
    }

}
