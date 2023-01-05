<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Feature\Mappers;

use App\Models\Exam;
use App\Models\Ssd;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Domain\ExamBlockStudyPlanDTO;
use App\Mappers\Implementations\ExamOptionMapperImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Description of ExamOptionMapperImplTest
 *
 * @author piero
 */
class ExamOptionMapperImplTest extends TestCase{
    
    use RefreshDatabase;
    
    private $mapper;
    
    protected function setUp(): void{
        parent::setUp();
        
        $this->mapper = new ExamOptionMapperImpl();
    }
    
    public function test_toTDO() {
        Ssd::factory(3)->create();
        Course::factory()->create();
        Exam::factory(3)->create();
        ExamBlock::factory()->create();
        ExamBlockOption::factory()->create();
        $option = ExamBlockOption::first();
        $block = new ExamBlockStudyPlanDTO(1, 2, 9, null);
        
        $result = $this->mapper->toDTO($option, $block);
        
        $this->assertEquals($block, $result->getBlock());
        $this->assertEquals($block->getExamOption(1), $result);
        $this->assertEquals(
               [$option->id,
                9,
                $option->exam->name,
                $option->exam->ssd->code
                ],
               [$result->getId(),
                $result->getCfu(),
                $result->getExamName(),
                $result->getSsd()]);
    }
    
    public function test_toTDO_on_exam_with_null_ssD() {
        Ssd::factory(3)->create();
        Course::factory()->create();
        Exam::create([
            "name" => "esame test",
        ]);
        ExamBlock::factory()->create();
        ExamBlockOption::factory()->create();
        $option = ExamBlockOption::first();
        $block = new ExamBlockStudyPlanDTO(1, 2, 7, 1);
        
        $result = $this->mapper->toDTO($option, $block);
        
        $this->assertEquals($block, $result->getBlock());
        $this->assertEquals($block->getExamOption(1), $result);
        $this->assertEquals(
               [$option->id,
                7,
                $option->exam->name,
                $option->exam->ssd,
                ],
               [$result->getId(),
                $result->getCfu(),
                $result->getExamName(),
                $result->getSsd()]);
    }
}
