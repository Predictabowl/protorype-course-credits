<?php

namespace Database\Factories;

use App\Models\ExamFront;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFrontFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * 
     * This factory is not needed.
     * This table must be filled by the algorithm using all the other data,
     * this also mean that porabily should not be saved but calculated 
     * every time
     *
     * @var string
     */
    protected $model = ExamFront::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}
