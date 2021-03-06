<?php

namespace Database\Factories;

use App\Models\Ssd;
use Illuminate\Database\Eloquent\Factories\Factory;

class SsdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ssd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "code" => strtoupper($this->faker->bothify("???/##"))
        ];
    }
}
