<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\Ssd;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Exam::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "ssd_id" => $this->faker->numberBetween(1,Ssd::count()),
            "name" => $this->faker->sentence(),
            "exam_block_id" => $this->faker->numberBetween(1, ExamBlock::count())
            //"cfu" => $this->faker->numberBetween(3,12)
        ];
    }
}
