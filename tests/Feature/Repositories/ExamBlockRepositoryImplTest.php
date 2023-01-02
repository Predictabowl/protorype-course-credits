<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Exceptions\Custom\ExamNotFoundException;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\Ssd;
use App\Repositories\Implementations\ExamBlockRepositoryImpl;
use App\Repositories\Interfaces\ExamRepository;
use App\Support\Seeders\ExamSupport;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;
use function collect;

class ExamBlockRepositoryImplTest extends TestCase
{
    use RefreshDatabase;
    
    private ExamBlockRepositoryImpl $sut;
    private ExamRepository $examRepo;
    
    protected function setUp(): void {
        parent::setUp();
        $this->examRepo = $this->createMock(ExamRepository::class);
        
        $this->sut = new ExamBlockRepositoryImpl($this->examRepo);
    }
    
    public function test_get_when_not_present(){
        $result = $this->sut->get(2);
        
        $this->assertNull($result);
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
        
        $result = $this->sut->get(1);
        
        $block = ExamBlock::find(1);
        $this->assertEquals($block->attributesToArray(), $result->attributesToArray());
    }
    
    public function test_getFilteredByCourse_when_course_not_present() {
        $this->expectException(ModelNotFoundException::class);
        
        $this->sut->getFilteredByCourse(3);
    }


    public function test_getFilteredByCrouse_without_options(){
        $course = Course::factory()->create();
        
        ExamBlock::factory(3)->create([
            "course_id" => $course
        ]);
        
        $result = $this->sut->getFilteredByCourse($course->id);
        
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
        
        $result = $this->sut->getFilteredByCourse($course->id);
        
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

    public function test_save_withNoCourse_shoulThrow(){
        $attributes = [
            "max_exams" => 1,
            "course_id" => 2,
            "cfu" => 6
        ];
        
        $examBlock = ExamBlock::make($attributes);
        
        $this->expectException(InvalidArgumentException::class);
        $this->sut->save($examBlock);
        
        $this->assertDatabaseCount("exam_blocks",0);
        
    } 
    
    public function test_saveWithId_notNull_ShouldThrow(){
        $course = Course::factory()->make();
        $course->save();
        
        $attributes = [
            "id" => 2,
            "max_exams" => 1,
            "course_id" => $course->id,
            "cfu" => 6
        ];
        $examBlock = ExamBlock::make($attributes);
        
        $this->expectException(InvalidArgumentException::class);
        $this->sut->save($examBlock);
        
        $this->assertDatabaseCount("exam_blocks",0);
    }
   
    public function test_save_newExamBlock_Success(){
        $course = Course::factory()->make();
        $course->save();
        
        $attributes = [
            "max_exams" => 1,
            "course_id" => $course->id,
            "cfu" => 6
        ];
        
        $examBlock = ExamBlock::make($attributes);
        
        $bResult = $this->sut->save($examBlock);
        
        $this->assertTrue($bResult);
        $this->assertDatabaseCount("exam_blocks", 1);
        $this->assertDatabaseHas("exam_blocks", $attributes);
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
        ]);
        $examBlock->save();
        $newEB = $examBlock;
        $newEB->max_exams = 3;
        $newEB->cfu = 9;
     
        $this->sut->update($newEB);
        
        $loaded = ExamBlock::all()->first();
        $this->assertEquals(3, $loaded->max_exams);
        $this->assertEquals(9, $loaded->cfu);
    }
    
    public function test_attachExam_whenBlockMissing(){
        $this->expectException(ExamBlockNotFoundException::class);
        $this->sut->attachExam(2, 3);
    }
    
    public function test_attachExam_whenExamMissing(){
        Course::factory()->create();
        $examBlock = ExamBlock::factory()->create();
        
        $this->expectException(ExamNotFoundException::class);
        $this->sut->attachExam($examBlock -> id, 3);
    }
    
    public function test_attachExam_success(){
        Course::factory()->create();
        Ssd::factory()->create();
        ExamBlock::factory(2)->create();
        Exam::factory(4)->create();
        $examBlock = ExamBlock::factory()->create();
        $exam = Exam::factory()->create();
        
        $return = $this->sut->attachExam($examBlock -> id, $exam->id);
        
        $this->assertTrue($return);
        
        $ebLoaded = Exam::find($exam->id)->examBlockOptions->first()->examBlock;
        $this->assertEquals($examBlock->id, $ebLoaded->id);
        
        $examLoaded = ExamBlock::find($examBlock->id)->examBlockOptions->first()->exam;
        $this->assertEquals($exam->id, $exam->id);
        
        $this->assertDatabaseCount("exam_block_options", 1);
    }
    
    public function test_attachExam_whenAlreadyAttached_shouldNotDuplicate(){
        Course::factory()->create();
        Ssd::factory()->create();
        ExamBlock::factory(2)->create();
        Exam::factory(4)->create();
        $examBlock = ExamBlock::factory()->create();
        $exam = Exam::factory()->create();
        ExamBlockOption::create([
            "exam_id" => $exam->id,
            "exam_block_id" => $examBlock->id
        ]);
        
        $return = $this->sut->attachExam($examBlock -> id, $exam->id);
        
        $this->assertFalse($return);
        $this->assertDatabaseCount("exam_block_options", 1);
    }
    
    public function test_delete_whenMissing(){       
        $return = $this->sut->delete(2);
        
        $this->assertFalse($return);
    }
    
    public function test_delete_examsOnCascade(){
        Ssd::factory(5)->create();
        Course::factory()->create();
        ExamBlock::factory()->create();
        $examBlock = ExamBlock::first();
        Exam::factory(3)->create();
        $exams = Exam::all();
        foreach($exams as $exam){
            ExamBlockOption::create([
                "exam_id" => $exam->id,
                "exam_block_id" => $examBlock->id
            ]);
        }
        $freeChoice = ExamSupport::getFreeChoiceExam();
        ExamBlockOption::create([
            "exam_id" => $freeChoice->id,
            "exam_block_id" => $examBlock->id
        ]);
        
        $this->examRepo->expects($this->once())
                ->method("deleteBatch")
                ->with(collect([
                    $exams->get(0)->id,
                    $exams->get(1)->id,
                    $exams->get(2)->id,
                    $freeChoice->id
                ]));
        
        $result = $this->sut->delete($examBlock->id);
        
        $this->assertTrue($result);
        $this->assertDatabaseCount("exam_block_options", 0);
        $this->assertDatabaseCount("exam_blocks", 0);
    }
}
