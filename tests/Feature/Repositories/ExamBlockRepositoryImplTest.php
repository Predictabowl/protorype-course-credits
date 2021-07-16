<?php

namespace Tests\Feature\Repositories;


use App\Repositories\Implementations\ExamBlockRepositoryImpl;
use App\Domain\ExamBlockDTO;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\Course;
use App\Models\Ssd;
use App\Models\Exam;
use App\Models\Front;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class ExamBlockRepositoryImplTest extends TestCase
{
    use RefreshDatabase;
    
    private $repository;
    
    protected function setUp(): void {
        parent::setUp();
        $this->repository = new ExamBlockRepositoryImpl();
    }
    
    public function test_get_block_without_options()
    {
        $block = ExamBlock::factory()->create([
            "course_id" => Course::factory()->create()
        ]);
        
        $sut = $this->repository->get(1);
        
        $this->assertEquals(
                [$block->id, $block->max_exams],
                [$sut->getId(), $sut->getNumExams()]);
        
    }
    
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
        
        $sut = $this->repository->get(1)->getExamOption(1);
        
        
        $this->assertEquals(
                [$option->id, $option->exam->ssd->code, $option->exam->cfu, $option->exam->name],
                [$sut->getId(), $sut->getSsd(), $sut->getCfu(), $sut->getExamName()]);
        $this->assertCount(3, $sut->getCompatibleOptions());
        
        $compatibiles = $sut->getCompatibleOptions();
        $this->assertEquals(
                [$option->ssds->first()->code, $option->ssds->get(1)->code, $option->ssds->last()->code],
                [$compatibiles[0],$compatibiles[1],$compatibiles[2]]);
        
    }
    
    public function test_getFromFront_without_options(){
        $course = Course::factory()->create();
        User::factory()->create();
        $front = Front::factory()->create([
            "course_id" => $course
        ]);
        
        ExamBlock::factory(3)->create([
            "course_id" => $course
        ]);
        
        $sut = $this->repository->getFromFront($front->id);
        
        $this->assertCount(3,$sut);
        $this->assertContainsOnlyInstancesOf(ExamBlockDTO::class, $sut);
    }
    
    public function test_getFromFront_with_options(){
        $course = Course::factory()->create();
        User::factory()->create();
        $front = Front::factory()->create([
            "course_id" => $course
        ]);
        Ssd::factory(4)->create();
        
        $blocks = ExamBlock::factory(3)->create([
            "course_id" => $course
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
        
        $sut = $this->repository->getFromFront($front->id);
        
        $this->assertCount(3,$sut[0]->getExamOptions());
        $this->assertCount(2,$sut[1]->getExamOptions());
        $this->assertCount(1,$sut[2]->getExamOptions());
    }

}
