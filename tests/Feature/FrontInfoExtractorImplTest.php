<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\Ssd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FrontInfoExtractorImplTest  extends TestCase
{

    use RefreshDatabase, WithFaker;

    private $course;

    protected function setUp(): void 
    {
        parent::setUp();
        $this->populateDB();
    }



    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_learning()
    {
        dd(Course::first());
    }


    private function populateDB()
    {
        $this->seed();
        
/*        
        $this->course = Course::factory()->create([
            "code" => "code01",
            "name" => "Course Test"
        ]);
        

        $ssds = Ssd::factory()->create(10);

        $block1 = ExamBlock::factory()->create([
            "cfu" => 12,
            "max_exams" => 2,
            "course_id" => $this->course->id
        ]);

        $block2 = ExamBlock::factory()->create([
            "cfu" => 18,
            "max_exams" => 1,
            "course_id" => $this->course->id
        ]);

        $exam1 = Exam::factory()->create([
            "ssd_id" => 1,
            "name" => "test exam 01",
            "cfu" => 12
        ]);

        $exam2 = Exam::factory()->create([
            "ssd_id" => 2,
            "name" => "test exam 02",
            "cfu" => 12
        ]);

        $exam3 = Exam::factory()->create([
            "ssd_id" => 3,
            "name" => "test exam 03",
            "cfu" => 9
        ]);        

        $exam4 = Exam::factory()->create([
            "ssd_id" => 4,
            "name" => "test exam 04",
            "cfu" => 6
        ]);        


        $option1 = ExamBlockOption::factory()->create([
            "exam_id" => $exam1->id,
            "exam_block_id" => $block1->id
        ]);

        $option2 = ExamBlockOption::factory()->create([
            "exam_id" => $exam2->id,
            "exam_block_id" => $block1->id
        ]);

        $option3 = ExamBlockOption::factory()->create([
            "exam_id" => $exam3->id,
            "exam_block_id" => $block2->id
        ]);*/
    }


}