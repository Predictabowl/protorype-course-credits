<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\ExamBlockOptionSsd;
use App\Models\Front;
use App\Models\Ssd;
use App\Models\TakenExam;
use App\Models\User;
use App\Seeders\GenerateSSD;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        User::factory()->create();
        User::factory()->create([
            "email" => "mario@email.com",
            "password" => Hash::make("password")
        ]);
        GenerateSSD::createAll();
        
        Course::factory()->create();

        Front::factory()->create(["user_id" => 1]);
        Front::factory()->create(["user_id" => 2]);

        Exam::factory(20)->create();
        ExamBlock::factory(10)->create();
        ExamBlockOption::factory(10)->create();
        TakenExam::factory(30)->create();
        ExamBlockOptionSsd::factory(40)->create();
        /*Exam::first()->courses()->create([
            "code" => "cl1",
            "name" => "Corso creato per test"
        ]);*/
    }
}
