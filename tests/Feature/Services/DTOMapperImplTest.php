<?php

namespace Tests\Feature\Services;


use App\Services\Implementations\DTOMapperImpl;
use App\Domain\ExamBlockDTO;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\TakenExam;
use App\Models\Course;
use App\Models\Ssd;
use App\Models\Exam;
use App\Models\Front;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class DTOMapperImplTest extends TestCase
{
    use RefreshDatabase;
    
    private $mapper;
    
    protected function setUp(): void {
        parent::setUp();
        $this->mapper = new DTOMapperImpl();
    }
    
    public function test_block_mapper()
    {
        $block = ExamBlock::factory()->make([
            "course_id" => Course::factory()->make()
        ]);
        
        $sut = $this->mapper->mapExamBlock($block);
        
        $this->assertEquals(
                [$block->id, $block->max_exams],
                [$sut->getId(), $sut->getNumExams()]);
        
    }
    
    public function test_takeExam_mapper()
    {
        $taken = TakenExam::factory()->make([
            "ssd_id" => Ssd::factory()->create(),
            "front_id" => Front::factory()->create([
               "user_id" => User::factory()->create(),
                "course_id" => Course::factory()->create()
            ])
        ]);
        
        
        $sut = $this->mapper->mapTakenExam($taken);
        
        $this->assertEquals(
                [$taken->id, $taken->ssd->code, $taken->cfu, $taken->name],
                [$sut->getId(), $sut->getSsd(), $sut->getCfu(), $sut->getExamName()]);
        
    }
    
    public function test_examOption_mapper()
    {
        $block = new ExamBlockDTO(1, 2);
        $ssds = Ssd::factory(3)->create();
        $option = ExamBlockOption::factory()->create([
            "exam_id" => Exam::factory()->create(),
            "exam_block_id" => ExamBlock::factory()->create([
                "course_id" => Course::factory()->create()
            ])
        ]);
        $option->ssds()->attach($ssds);
        
        $sut = $this->mapper->mapExamOption($option, $block);
        
        $this->assertEquals(
                [$option->id, $option->exam->ssd->code, $option->exam->cfu, $option->exam->name],
                [$sut->getId(), $sut->getSsd(), $sut->getCfu(), $sut->getExamName()]);
        $this->assertSame($block, $sut->getBlock());
        $this->assertSame($sut, $block->getExamOption($sut->getId())); //this should be responsability of ExamOptionDTO unit test 
        $this->assertCount(3, $sut->getCompatibleOptions());
        
        $compatibiles = $sut->getCompatibleOptions();
        $this->assertEquals(
                [$option->ssds->first()->code, $option->ssds->get(1)->code, $option->ssds->last()->code],
                [$compatibiles[0],$compatibiles[1],$compatibiles[2]]);
        
    }
}
