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
use Database\Seeders\Generators\GenerateSSD;
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
        $user1 = User::factory()->create();
        $user2 = User::factory()->create([
            "email" => "mario@email.com",
            "password" => Hash::make("password")
        ]);
        GenerateSSD::createAll();
        
        Course::factory()->create();

        $front1 = Front::factory()->create(["user_id" => 1]);
        $front2 = Front::factory()->create(["user_id" => 2]);
        $user1->front()->associate($front1);
        $user2->front()->associate($front2);
        

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
