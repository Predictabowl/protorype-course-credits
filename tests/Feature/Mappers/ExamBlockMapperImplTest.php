<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Feature\Mappers;

use App\Models\ExamBlock;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Ssd;
use App\Models\ExamBlockOption;
use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;
use App\Mappers\Implementations\ExamBlockMapperImpl;
use App\Mappers\Interfaces\ExamOptionMapper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Description of ExamBlockMapperImplTest
 *
 * @author piero
 */
class ExamBlockMapperImplTest extends TestCase{
    
    use RefreshDatabase;
    
    private $optionMapper;
    
    protected function setUp(): void{
        parent::setUp();
        
        $this->optionMapper = $this->createMock(ExamOptionMapper::class);
        app()->instance(ExamOptionMapper::class, $this->optionMapper);
    }
    
    public function test_toDTO(){
        Ssd::factory()->create();
        Course::factory()->create();
        Exam::factory(3)->create();
        $model = ExamBlock::create([
            "max_exams" => 2,
            "course_id" => 1,
            "cfu" => 10
        ]);
        ExamBlockOption::factory(3)->create([
           "exam_block_id" => 1,
        ]);
        $block = new ExamBlockDTO(5, 3, 7);
        
        $optionDto1 = new ExamOptionDTO(4, "nome 2", $block,"ssd6");
        $optionDto2 = new ExamOptionDTO(7, "nome test", $block, "ssd4");
        $optionDto3 = new ExamOptionDTO(13, "nome ad", $block, "ssd1");
        
        $options = ExamBlockOption::all();
        $this->optionMapper->expects($this->exactly(3))
                ->method("toDTO")
                ->withConsecutive(
                        [$options[0],$this->isInstanceOf(ExamBlockDTO::class)],
                        [$options[1],$this->isInstanceOf(ExamBlockDTO::class)],
                        [$options[2],$this->isInstanceOf(ExamBlockDTO::class)],
                    )
                ->willReturnOnConsecutiveCalls(
                        $optionDto1, $optionDto2, $optionDto3);
        
        $mapper = new ExamBlockMapperImpl();
        $result = $mapper->toDTO($model);
        
        $this->assertEquals(new ExamBlockDTO(1, 2, 10), $result);
    }
    
    
}
