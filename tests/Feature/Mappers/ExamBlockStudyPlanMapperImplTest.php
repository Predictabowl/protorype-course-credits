<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Feature\Mappers;

use App\Domain\ExamBlockStudyPlanDTO;
use App\Domain\ExamStudyPlanDTO;
use App\Mappers\Implementations\ExamBlockStudyPlanMapperImpl;
use App\Mappers\Interfaces\ExamStudyPlanMapper;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\Ssd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function app;

/**
 * Description of ExamBlockMapperImplTest
 *
 * @author piero
 */
class ExamBlockStudyPlanMapperImplTest extends TestCase{
    
    use RefreshDatabase;
    
    private ExamStudyPlanMapper $optionMapper;
    private ExamBlockStudyPlanMapperImpl $sut;
    
    protected function setUp(): void{
        parent::setUp();
        
        $this->optionMapper = $this->createMock(ExamStudyPlanMapper::class);
        app()->instance(ExamStudyPlanMapper::class, $this->optionMapper);
        $this->sut = new ExamBlockStudyPlanMapperImpl($this->optionMapper);
    }
    
    public function test_toDTO(){
        Ssd::factory(3)->create();
        Course::factory()->create();
        $model = ExamBlock::create([
            "max_exams" => 2,
            "course_id" => 1,
            "cfu" => 10,
            "courseYear" => 1
        ]);
        $ssd1 = Ssd::find(1);
        $ssd2 = Ssd::find(2);
        $model->ssds()->attach($ssd1);
        $model->ssds()->attach($ssd2);
        Exam::factory(3)->create();
        $block = new ExamBlockStudyPlanDTO(5, 3, 7, 2);
        
        $optionDto1 = new ExamStudyPlanDTO(4, "nome 2", $block,"ssd6");
        $optionDto2 = new ExamStudyPlanDTO(7, "nome test", $block, "ssd4");
        $optionDto3 = new ExamStudyPlanDTO(13, "nome ad", $block, "ssd1");
        
        
        $exams = Exam::all();
        $this->optionMapper->expects($this->exactly(3))
                ->method("toDTO")
                ->withConsecutive(
                        [$exams[0],$this->isInstanceOf(ExamBlockStudyPlanDTO::class)],
                        [$exams[1],$this->isInstanceOf(ExamBlockStudyPlanDTO::class)],
                        [$exams[2],$this->isInstanceOf(ExamBlockStudyPlanDTO::class)],
                    )
                ->willReturnOnConsecutiveCalls(
                        $optionDto1, $optionDto2, $optionDto3);
        
        $result = $this->sut->toDTO($model);
        
        $this->assertEquals(1, $result->getId());
        $this->assertEquals(2, $result->getNumExams());
        $this->assertEquals(10, $result->getCfu());
        $this->assertEquals(1, $result->getCourseYear());
        $this->assertEquals($ssd1->code, $result->getCompatibleOptions()->first());
        $this->assertEquals($ssd2->code, $result->getCompatibleOptions()->get(1));
    }
    
    
}
