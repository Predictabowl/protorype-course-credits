<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\Custom\CourseNotFoundException;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\Ssd;
use App\Repositories\Implementations\ExamBlockRepositoryImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ExamBlockRepositoryImplTest extends TestCase
{
    use RefreshDatabase;
    
    private ExamBlockRepositoryImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->sut = new ExamBlockRepositoryImpl();
    }
    
    public function test_getWithFullDepth_when_not_present(){
        $result = $this->sut->getWithFullDepth(2);
        
        $this->assertNull($result);
    }
  
    public function test_getWithFullDepth_block_with_eagerLoading()
    {
        $course = Course::factory()->create();
        $ssds = Ssd::factory(3)->create();
        
        $block = ExamBlock::factory()->create([
            "course_id" => $course
        ]);
        
        $exam = Exam::factory()->create([
            "exam_block_id" => $block
        ]);
        $block->ssds()->attach($ssds);
        
        $result = $this->sut->getWithFullDepth($block->id);
        
        $this->assertTrue($result->relationLoaded("ssds"));
        $this->assertTrue($result->relationLoaded("exams"));
        $exams = $result->exams->each(function ($value){
            $this->assertTrue($value->relationLoaded("ssd"));
        });
        $this->assertEquals(ExamBlock::first()->all(), $result->all());
    }
    
    public function test_get_lock_with_lazyLoading()
    {
        $course = Course::factory()->create();
        $ssds = Ssd::factory(3)->create();
        
        $block = ExamBlock::factory()->create([
            "course_id" => $course
        ]);
        
        $exam = Exam::factory()->create([
            "exam_block_id" => $block
        ]);
        $block->ssds()->attach($ssds);
        
        $result = $this->sut->get($block->id);
        
        $this->assertEmpty($result->relationsToArray());
        $this->assertEquals(ExamBlock::first()->all(), $result->all());
    }
    
    public function test_getWithSsds_eagerLoading()
    {
        $course = Course::factory()->create();
        $ssds = Ssd::factory(3)->create();
        
        $block = ExamBlock::factory()->create([
            "course_id" => $course
        ]);
        
        $exam = Exam::factory()->create([
            "exam_block_id" => $block
        ]);
        $block->ssds()->attach($ssds);
        
        $result = $this->sut->getWithSsds($block->id);
        
        $this->assertTrue($result->relationLoaded("ssds"));
        $this->assertFalse($result->relationLoaded("exams"));
        $this->assertEquals(ExamBlock::first()->all(), $result->all());
    }
    
    public function test_save_withNoCourse_shouldThrow(){
        $attributes = [
            "max_exams" => 1,
            "course_id" => 2,
            "cfu" => 6
        ];
        
        $examBlock = ExamBlock::make($attributes);
        
        $this->expectException(CourseNotFoundException::class);
        $this->sut->save($examBlock);
        
        $this->assertDatabaseCount("exam_blocks",0);
        
    } 
    
    public function test_saveWithId_notNull_ShouldThrow(){
        $course = Course::factory()->make();
        $attributes = [
            "id" => 2,
            "max_exams" => 1,
            "course_id" => $course->id,
            "cfu" => 6
        ];
        $examBlock = ExamBlock::make($attributes);
        $course->save();
        
        $this->expectException(InvalidArgumentException::class);
        $this->sut->save($examBlock);
        
        $this->assertDatabaseCount("exam_blocks",0);
    }
   
    public function test_save_newExamBlock_Success(){
        $course = Course::factory()->create();
        $attributes = [
            "max_exams" => 1,
            "course_id" => $course->id,
            "cfu" => 6,
            "courseYear" => null
        ];
        $examBlock = ExamBlock::make($attributes);
        
        $result = $this->sut->save($examBlock);
        
        $saved = ExamBlock::first();
        $this->assertDatabaseCount("exam_blocks", 1);
        $this->assertDatabaseHas("exam_blocks", $attributes);
        $this->assertEquals($saved->attributesToArray(), $result->attributesToArray());
    }
    
    public function test_update_examBlock_whenBlockMissing_shouldThrow(){
        $course = Course::factory()->create();
        $examBlock = new ExamBlock([
            "id" => 1,
            "max_exams" => 2,
            "course_id" => $course->id,
            "cfu" => 6,
        ]);
     
        $this->expectException(ExamBlockNotFoundException::class);
        $this->sut->update($examBlock);
    }
    
    public function test_update_examBlock_success(){
        $course = Course::factory()->create();
        $examBlock = new ExamBlock([
            "id" => null,
            "max_exams" => 2,
            "course_id" => $course->id,
            "cfu" => 6,
            "courseYear" => 2
        ]);
        $examBlock->save();
        $attributes = [
            "id" => $examBlock->id,
            "max_exams" => 3,
            "cfu" => 9,
            "courseYear" => 1,
            ];
        $newEB = ExamBlock::factory()->make($attributes);
        $newEB->course_id = $course->id+1;
     
        $result = $this->sut->update($newEB);
        
        $this->assertDatabaseHas("exam_blocks", $attributes);
        $this->assertEquals(ExamBlock::first()->all(), $result->all());
    }

    public function test_delete_whenMissing(){       
        $return = $this->sut->delete(2);
        
        $this->assertFalse($return);
    }
    
    public function test_delete_examBlock_shouldDeleteExams(){
        Ssd::factory(5)->create();
        Course::factory()->create();
        ExamBlock::factory()->create();
        $examBlock = ExamBlock::first();
        Exam::factory(3)->create();
        
        $result = $this->sut->delete($examBlock->id);
        
        $this->assertTrue($result);
        $this->assertDatabaseCount("exams", 0);
        $this->assertDatabaseCount("exam_blocks", 0);
    }
    
    public function test_attachSsd(){
        $ssd = Ssd::factory()->create();
        Course::factory()->create();
        $examBlock = ExamBlock::factory()->create();
        
        $this->sut->attachSsd($examBlock->id, $ssd->id);
        
        $this->assertDatabaseHas("exam_block_ssd", [
            "ssd_id" => $ssd->id,
            "exam_block_id" => $examBlock->id]);
    }
    
    public function test_detachSsd(){
        $ssd = Ssd::factory()->create();
        $ssd2 = Ssd::factory()->create();
        Course::factory()->create();
        $examBlock = ExamBlock::factory()->create();
        $examBlock->ssds()->attach($ssd);
        $examBlock->ssds()->attach($ssd2);
        $examBlock->save();
        
        $this->sut->detachSsd($examBlock->id, $ssd->id);
        
        $this->assertDatabaseCount("exam_block_ssd", 1);
        $this->assertDatabaseMissing("exam_block_ssd", [
            "ssd_id" => $ssd->id,
            "exam_block_id" => $examBlock->id]);
        $this->assertDatabaseHas("exam_block_ssd", [
            "ssd_id" => $ssd2->id,
            "exam_block_id" => $examBlock->id]);
    }
}
