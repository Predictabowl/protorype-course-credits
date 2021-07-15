<?php

namespace Database\Factories;

use App\Models\ExamBlockOptionSsd;
use App\Models\Ssd;
use App\Models\ExamBlockOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamBlockOptionSsdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExamBlockOptionSsd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "ssd_id" => $this->faker->numberBetween(1, Ssd::count()),
            "exam_block_option_id" => $this->faker->numberBetween(1, ExamBlockOption::count())
        ];
    }
}
