<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Exam;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Exam::factory(20)->create();
        Course::factory(2)->create();

        /*Exam::first()->courses()->create([
            "code" => "cl1",
            "name" => "Corso creato per test"
        ]);*/
    }
}
