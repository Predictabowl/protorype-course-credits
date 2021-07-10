<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamBlockOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExamBlockOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "exam_id" => $this->faker->numberBetween(1,Exam::count()),
            "exam_block_id" => $this->faker->numberBetween(1,ExamBlock::count())
        ];
    }
}
