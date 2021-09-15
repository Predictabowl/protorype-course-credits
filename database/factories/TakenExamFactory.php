<?php

namespace Database\Factories;

use App\Models\Front;
use App\Models\Ssd;
use App\Models\TakenExam;
use Illuminate\Database\Eloquent\Factories\Factory;

class TakenExamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TakenExam::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->sentence(4),
            "ssd_id" => $this->faker->numberBetween(1,Ssd::count()),
            "front_id" => $this->faker->numberBetween(1,Front::count()),
            "cfu" => $this->faker->numberBetween(3,12),
            "grade" => $this->faker->numberBetween(18,30),
            "courseYear" => $this->faker->numberBetween(1,3)
        ];
    }
}
