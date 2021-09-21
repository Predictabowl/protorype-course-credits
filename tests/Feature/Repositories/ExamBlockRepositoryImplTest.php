<?php

namespace Tests\Feature\Repositories;


use App\Repositories\Implementations\ExamBlockRepositoryImpl;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\Course;
use App\Models\Ssd;
use App\Models\Exam;
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
     * This doesn't actually test the eager load, so the added options are
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
    
    public function test_getFilteredByCourse_when_course_not_present() {
        $this->expectException(ModelNotFoundException::class);
        
        $this->repository->getFilteredByCourse(3);
    }


    public function test_getFilteredByCrouse_without_options(){
        $course = Course::factory()->create();
        
        ExamBlock::factory(3)->create([
            "course_id" => $course
        ]);
        
        $result = $this->repository->getFilteredByCourse($course->id);
        
        $this->assertCount(3,$result);
        $this->assertContainsOnlyInstancesOf(ExamBlock::class, $result);
        // this test was cut short to save time
    }
    
    public function test_getFilteredByCourse_with_options(){
        $course = Course::factory()->create();
        Ssd::factory(4)->create();
        
        $blocks = ExamBlock::factory(3)->create([
            "course_id" => $course
        ]);
        
        ExamBlock::factory(3)->create([
            "course_id" => Course::factory()->create()
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
        
        $result = $this->repository->getFilteredByCourse($course->id);
        
        $this->assertCount(3,$result);
        $this->assertContainsOnlyInstancesOf(ExamBlock::class, $result);
        $this->assertEquals(ExamBlock::find(1)->attributesToArray(),
                $result[0]->attributesToArray());
        $this->assertEquals(ExamBlock::find(2)->attributesToArray(),
                $result[1]->attributesToArray());
        $this->assertEquals(ExamBlock::find(3)->attributesToArray(),
                $result[2]->attributesToArray());
        // incomplete test
    }

}
