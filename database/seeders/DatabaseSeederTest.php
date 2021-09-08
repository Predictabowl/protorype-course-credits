<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\ExamBlockOptionSsd;
use App\Models\Front;
use App\Models\Role;
use App\Models\TakenExam;
use App\Models\RoleUser;
use App\Models\User;
use App\Support\Seeders\GenerateSSD;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeederTest2 extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        GenerateSSD::createAll();
        
        Role::create([
            "id" => 1,
            "name" => "admin"
        ]);
        Role::create([
            "id" => 2,
            "name" => "supervisor"
        ]);
        // \App\Models\User::factory(10)->create();
        User::factory()->create([
            "id" => 1,
            "name" => "Amministratore Temporaneo",
            "email" => "admin@email.org",
            "password" => Hash::make("password")
        ]);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create([
            "email" => "mario@email.com",
            "password" => Hash::make("password")
        ]);
        
        RoleUser::create([
            "user_id" => 1,
            "role_id" => 1
        ]);
        
        Course::factory()->create();

        $front1 = Front::factory()->create(["user_id" => 1]);
        $front2 = Front::factory()->create(["user_id" => 2]);
        //$user1->front()->associate($front1);
        //$user2->front()->associate($front2);
        

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
