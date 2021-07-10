<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamBlockOption;
use App\Models\ExamExamBlockOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamExamBlockOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExamExamBlockOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "exam_id" => $this->faker->numberBetween(1,Exam::count()),
            "exam_block_option_id" => $this->faker->numberBetween(1,ExamBlockOption::count())
        ];
    }
}