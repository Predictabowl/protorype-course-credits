<?php

// Not used anymore, just keeping around for the populatedatabse copy-paste

namespace Tests\Feature\Services;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\Front;
use App\Models\Ssd;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
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




    public function test_learning()
    {
        /*$result = Course::first()->examBlocks
            ->map(fn ($block) => $block->examBlockOptions
                ->map(fn ($option) => $option->examApproved))
            ->flatten();*/

        // DB::listen(function($query){
        //     logger($query->sql, $query->bindin);
        // });
        $front = Front::first();

        $result = $front->course->load(["examBlocks.examBlockOptions.examApproved"]);
        //$result = Course::first()->with("examBlocks.examBlockOptions.examApproved")->get();
            /*->map(fn ($block) => $block->examBlockOptions
                ->map(fn ($option) => $option->examApproved));*/
        dd($result);
    }


    private function populateDB()
    {
        //$this->seed();
        
        User::factory()->create();

        $this->course = Course::factory()->create([
            "code" => "code01",
            "name" => "Course Test"
        ]);

        Front::factory()->create([
            "course_id" => 1,
            "user_id" => 1
        ]);
        

        $ssds = Ssd::factory(10)->create();

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
        ]);
    }


}