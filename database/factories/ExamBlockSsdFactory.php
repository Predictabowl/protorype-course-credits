<?php

namespace Database\Factories;

use App\Models\ExamBlock;
use App\Models\ExamBlockSsd;
use App\Models\Ssd;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamBlockSsd>
 */
class ExamBlockSsdFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExamBlockSsd::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "ssd_id" => $this->faker->numberBetween(1, Ssd::count()),
            "exam_block_id" => $this->faker->numberBetween(1, ExamBlock::count())
        ];
    }
}
