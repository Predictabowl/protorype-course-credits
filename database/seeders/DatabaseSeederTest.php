<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Front;
use App\Models\Ssd;
use App\Models\ExamBlock;
use App\Models\Exam;
use App\Models\ExamBlockOption;
use App\Models\TakenExam;

class DatabaseSeederTest extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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


        TakenExam::factory()->create([
            "name" => "test exam 01 mod 1",
            "cfu" => 6,
            "ssd_id" => 1,
            "front_id" => 1
        ]);

        TakenExam::factory()->create([
            "name" => "test exam 02 mod 2",
            "cfu" => 9,
            "ssd_id" => 2,
            "front_id" => 1
        ]);

        TakenExam::factory()->create([
            "name" => "test exam 03 mod 3",
            "cfu" => 5,
            "ssd_id" => 3,
            "front_id" => 1
        ]);
        
        TakenExam::factory()->create([
            "name" => "test exam 03 mod 3",
            "cfu" => 18,
            "ssd_id" => 3,
            "front_id" => 1
        ]);
    }
}
