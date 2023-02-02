<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Feature\Repositories;

use App\Exceptions\Custom\ExamNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\Ssd;
use App\Repositories\Implementations\ExamRepositoryImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

/**
 * Description of ExamRepositoryImplTest
 *
 * @author piero
 */
class ExamRepositoryImplTest extends TestCase{
    
    use RefreshDatabase;
    
    private ExamRepositoryImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        $this->sut = new ExamRepositoryImpl();
        Course::factory()->create();
        Ssd::factory()->create();
        ExamBlock::factory()->create();
    }
    
    public function test_get_whenNotPresent(){
        $get = $this->sut->get(3);
        
        $this->assertNull($get);
    }
    
    public function test_get_success(){

        Exam::factory()->create([
            "id" => 2,
            "code" => null
        ]);
        $fixture = Exam::first();
        
        $get = $this->sut->get(2);
        
        $this->assertEquals($fixture->attributesToArray(), $get->attributesToArray());
    }
    
    public function test_save_whenInvalidSsd_shouldThrow(){
        $fixture = Exam::factory()->make([
            "ssd_id" => 10
        ]);
        
        $this->expectException(SsdNotFoundException::class);
        $this->sut->save($fixture);
    }
    
    public function test_save_whenIdIsSet_shouldThrow(){
        $fixture = Exam::factory()->make();
        $fixture->id = 10;
                
        $this->expectException(InvalidArgumentException::class);
        $this->sut->save($fixture);
    }
    
    public function test_save_successfull(){
        $ssd = Ssd::factory()->create();
        $toSave = Exam::factory()->make([
            "code" => null,
            "ssd_id" => $ssd->id,
            "name" => "test name"
        ]);
        
        $bSaved = $this->sut->save($toSave);
        
        $this->assertEquals($bSaved,$toSave);
        $this->assertDatabaseCount("exams",1);
        $found = Exam::first();
        $this->assertEquals("test name", $found->name);
        $this->assertEquals($ssd->id, $found->ssd_id);
    }
    
   public function test_save_whenSsdId_isMissing(){
        $ssd = Ssd::factory()->create();
        $toSave = Exam::factory()->make([
            "name" => "test name",
            "free_choice" => true,
            "ssd_id" => null
        ]);
        
        $bSaved = $this->sut->save($toSave);
        
        $this->assertEquals($bSaved,$toSave);
        $this->assertDatabaseCount("exams",1);
        $found = Exam::first();
        $this->assertEquals("test name", $found->name);
        $this->assertNull($found->ssd_id);
    }    
    
    public function test_update_whenInvalidSsd_shouldThrow(){
        $exam = Exam::factory()->create();
        $exam->ssd_id += 1;
        
        $this->expectException(SsdNotFoundException::class);
        $this->sut->update($exam);
    }
    
    public function test_update_withInvalId_shouldThrow(){
        $exam = Exam::factory()->make();
        $exam->id = 10;
        
        $this->expectException(ExamNotFoundException::class);
        $this->sut->update($exam);
    }
    
    public function test_update_success(){
        $exam = Exam::factory()->create([
            "name" => "original name",
            "ssd_id" => Ssd::factory()->create(),
            "free_choice" => false
        ]);

        $ssd = Ssd::factory()->create();
        $loaded = Exam::first();
        $this->assertEquals("original name",$loaded->name);
        
        $updatedExam = new Exam([
                "id" => $exam->id,
                "name" => "new name",
                "ssd_id" => $ssd->id,
                "exam_block_id" => $exam->exam_block_id+1,
                "free_choice" => true
            ]);
        
        $result = $this->sut->update($updatedExam);
        
        $modified = Exam::first();
        $this->assertDatabaseCount("exams",1);
        $this->assertEquals("new name",$modified->name);
        $this->assertEquals($ssd->id, $modified->ssd_id);
        $this->assertEquals($exam->exam_block_id, $modified->exam_block_id);
        $this->assertEquals(1, $modified->free_choice);
        $this->assertEquals($modified->toArray(), $result->toArray());
    }
    
    public function test_update_whenSsdIsNull(){
        $exam = Exam::factory()->create([
            "name" => "original name",
            "ssd_id" => Ssd::factory()->create(),
            "free_choice" => false
        ]);

        $loaded = Exam::first();
        $this->assertEquals("original name",$loaded->name);
        
        $updatedExam = new Exam([
                "id" => $exam->id,
                "name" => "new name",
                "ssd_id" => null,
                "exam_block_id" => $exam->exam_block_id+1,
                "free_choice" => true
            ]);
        
        $result = $this->sut->update($updatedExam);
        
        $modified = Exam::first();
        $this->assertDatabaseCount("exams",1);
        $this->assertEquals("new name",$modified->name);
        $this->assertNull($modified->ssd_id);
        $this->assertEquals($exam->exam_block_id, $modified->exam_block_id);
        $this->assertEquals(1, $modified->free_choice);
        $this->assertEquals($modified->toArray(), $result->toArray());
    }
    
    public function test_deleteExam(){
        $exam = Exam::factory()->create();
        
        $this->sut->delete($exam->id);
        
        $loaded = Exam::find($exam->id);
        $this->assertNull($loaded);
    }
    
    public function test_deleteBatch(){
        Ssd::factory(3)->create();
        $exams = Exam::factory(2)->create();
        $ids = $exams->map(function ($item){
                return $item->id;
            });
        
        $this->sut->deleteBatch($ids);
        
        $this->assertDatabaseCount("exams", 0);
        $this->assertDatabaseCount("ssds", 4);
    }
    
}
