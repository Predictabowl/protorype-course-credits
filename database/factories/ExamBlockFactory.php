<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\ExamBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamBlockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExamBlock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "cfu" => $this->faker->numberBetween(3,12),
            "max_exams" => $this->faker->numberBetween(1,3),
            "course_id" => $this->faker->numberBetween(1,Course::count())
        ];
    }
}
