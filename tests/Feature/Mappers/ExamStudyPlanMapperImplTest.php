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
use App\Mappers\Implementations\ExamStudyPlanMapperImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Description of ExamOptionMapperImplTest
 *
 * @author piero
 */
class ExamStudyPlanMapperImplTest extends TestCase{
    
    use RefreshDatabase;
    
    private ExamStudyPlanMapperImpl $sut;
    
    protected function setUp(): void{
        parent::setUp();
        
        $this->sut = new ExamStudyPlanMapperImpl();
    }
    
    public function test_toTdo() {
        Ssd::factory(3)->create();
        Course::factory()->create();
        ExamBlock::factory()->create();
        Exam::factory(3)->create();
        $exam = Exam::first();
        $block = new ExamBlockStudyPlanDTO(1, 2, 9, null);
        
        $result = $this->sut->toDTO($exam, $block);
        
        $this->assertEquals($block, $result->getBlock());
        $this->assertEquals($block->getExamOption(1), $result);
        $this->assertEquals(
               [$exam->id,
                9,
                $exam->name,
                $exam->ssd->code,
                false
                ],
               [$result->getId(),
                $result->getCfu(),
                $result->getExamName(),
                $result->getSsd(),
                $result->isFreeChoice()]);
    }
    
    public function test_toTdo_on_exam_with_null_ssd() {
        Ssd::factory(3)->create();
        Course::factory()->create();
        ExamBlock::factory()->create();
        Exam::factory()->create([
            "name" => "esame test",
            "free_choice" => true
        ]);
        $exam = Exam::first();
        $block = new ExamBlockStudyPlanDTO(1, 2, 7, 1);
        
        $result = $this->sut->toDTO($exam, $block);
        
        $this->assertEquals($block, $result->getBlock());
        $this->assertEquals($block->getExamOption(1), $result);
        $this->assertEquals(
               [$exam->id,
                7,
                $exam->name,
                $exam->ssd->code,
                true
                ],
               [$result->getId(),
                $result->getCfu(),
                $result->getExamName(),
                $result->getSsd(),
                $result->isFreeChoice()]);
    }
}
