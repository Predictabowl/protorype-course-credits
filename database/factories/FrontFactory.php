<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Front;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FrontFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Front::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => $this->faker->numberBetween(1,User::count()),
            "course_id" => $this->faker->numberBetween(1,Course::count()),
        ];
    }
}
