<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->sentence(4),
            //"code" => $this->faker->bothify("???-##"),
            "cfu" => 180,
            "finalExamCfu" => $this->faker->numberBetween(6, 12),
            "otherActivitiesCfu" => $this->faker->numberBetween(0, 9)
        ];
    }
}
