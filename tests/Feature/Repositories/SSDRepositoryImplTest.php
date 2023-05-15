<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Feature\Repositories;

use App\Models\Course;
use App\Models\ExamBlock;
use App\Models\Ssd;
use App\Repositories\Implementations\SSDRepositoryImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Description of SSDRepositoryImplTest
 *
 * @author piero
 */
class SSDRepositoryImplTest extends TestCase{
    
    use RefreshDatabase;
    
    private SSDRepositoryImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->sut = new SSDRepositoryImpl();
    }

    
    public function test_getSsd_whenMissing(){
        $notFound = $this->sut->getSsdFromCode("SSD/01");
        
        $this->assertNull($notFound);
    }
    
    public function test_getSsd_success() {
        Ssd::factory()->create();
        $ssd = Ssd::all()->first();
        
        $result = $this->sut->getSsdFromCode($ssd->code);
        
        $this->assertEquals($ssd, $result);
    }
    
    public function test_getSsdWithExamBlocks_whenMissing(){
        $notFound = $this->sut->getSsdFromCodeWithExamBlocks("SSD/01");
        
        $this->assertNull($notFound);
    }
    
    public function test_getSssWithExamBlocks_success() {
        Course::factory()->create();
        $ssd = Ssd::factory()->create();
        $examBlock = ExamBlock::factory()->create();
        $examBlock->ssds()->attach($ssd);
        $examBlock->save();
        
        $result = $this->sut->getSsdFromCodeWithExamBlocks($ssd->code);
        
        $relations = $result->relationsToArray();
        $this->assertEquals($ssd->all(), $result->all());
        $this->assertArrayHasKey("exam_blocks",$relations);
    }
    
    function test_getAll() {
        $result = $this->sut->getAll();
        
        $all = Ssd::all();
        $this->assertEquals($all, $result);
//        $result->each(function(Ssd $ssd){
//        });
    }
}
