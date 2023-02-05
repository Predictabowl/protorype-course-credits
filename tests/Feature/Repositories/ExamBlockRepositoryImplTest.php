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
    
    public function test_get_when_not_present(){
        $result = $this->sut->get(2);
        
        $this->assertNull($result);
    }
  
    /*
     * This doesn't actually test the eager load, so the added options are
     * bloat.
     */
    public function test_get_block_with_eagerLoading()
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
        
        $result = $this->sut->get(1);
        
        $block = ExamBlock::find(1);
        $this->assertEquals($block->attributesToArray(), $result->attributesToArray());
        $this->assertEquals(2, sizeof($result->getRelations()));
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
        $newEB = ExamBlock::factory()->make([
            "id" => $examBlock->id,
            "max_exams" => 3,
            "cfu" => 9,
            "courseYear" => 1,
            "course_id" => $course->id+1
            ]);
     
        $result = $this->sut->update($newEB);
        
        $loaded = ExamBlock::first();
        $this->assertEquals(3, $loaded->max_exams);
        $this->assertEquals(9, $loaded->cfu);
        $this->assertEquals($course->id, $loaded->course_id);
        $this->assertEquals(1, $loaded->courseYear);
        $this->assertEquals($loaded->attributesToArray(),
                $result->attributesToArray());
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
}
