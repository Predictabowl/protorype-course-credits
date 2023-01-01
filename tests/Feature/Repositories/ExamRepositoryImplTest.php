<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Feature\Repositories;

use App\Exceptions\Custom\ExamNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Models\Exam;
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
    
    private ExamRepositoryImpl $repository;
    
    protected function setUp(): void {
        parent::setUp();
        $this->repository = new ExamRepositoryImpl();
    }
    
    public function test_get_whenNotPresent(){
        $get = $this->repository->get(3);
        
        $this->assertNull($get);
    }
    
    public function test_get_success(){
        Ssd::factory()->create();
        $fixture = Exam::factory()->create([
            "id" => 2,
            "code" => null
        ]);
        
        $get = $this->repository->get(2);
        
        $this->assertEquals($fixture->attributesToArray(), $get->attributesToArray());
    }
    
    public function test_save_whenInvalidSsd_shouldThrow(){
        $fixture = Exam::factory()->make([
            "ssd_id" => 10
        ]);
        
        $this->expectException(SsdNotFoundException::class);
        $this->repository->save($fixture);
    }
    
    public function test_save_whenIdIsSet_shouldThrow(){
        $fixture = Exam::factory()->make();
        $fixture->id = 10;
                
        $this->expectException(InvalidArgumentException::class);
        $this->repository->save($fixture);
    }
    
    public function test_save_successfull(){
        $ssd = Ssd::factory()->create();
        $toSave = Exam::make([
            "code" => null,
            "ssd_id" => $ssd->id,
            "name" => "test name"
        ]);
        
        $bSaved = $this->repository->save($toSave);
        
        $this->assertEquals($bSaved,$toSave);
        $this->assertDatabaseCount("exams",1);
        $found = Exam::first();
        $this->assertEquals("test name", $found->name);
        $this->assertEquals($ssd->id, $found->ssd_id);
    }
    
    public function test_update_whenInvalidSsd_shouldThrow(){
        Ssd::factory()->create();
        $exam = Exam::factory()->create();
        $exam->ssd_id += 1;
        
        $this->expectException(SsdNotFoundException::class);
        $this->repository->update($exam);
    }
    
    public function test_update_withInvalId_shouldThrow(){
        $exam = Exam::factory()->make();
        $exam->id = 10;
        
        $this->expectException(ExamNotFoundException::class);
        $this->repository->update($exam);
    }
    
    public function test_update_success(){
        $exam = Exam::factory()->create([
            "name" => "original name",
            "ssd_id" => Ssd::factory()->create()
        ]);

        $loaded = Exam::first();
        $this->assertEquals("original name",$loaded->name);
        
        $exam->name = "new name";
        
        $bResult = $this->repository->update($exam);
        
        $this->assertEquals($bResult,$exam);
        $this->assertDatabaseCount("exams",1);
        $modified = Exam::first();
        $this->assertEquals("new name",$modified->name);
    }
    
}
