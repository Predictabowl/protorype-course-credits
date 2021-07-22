<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Ssd;
use App\Support\Seeders\GenerateSSD;

class DatabaseSeederReal extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            "name" => "Amministratore Temporaneo",
            "email" => "admin@email.org",
            "password" => Hash::make("password")
        ]);
        
        Role::create([
            "id" => 1,
            "name" => "admin"
        ]);
        
        Role::create([
            "id" => 2,
            "name" => "supervisor"
        ]);
        
        RoleUser::create([
            "user_id" => 1,
            "role_id" => 1
        ]);
        
        $course = Course::factory()->create([
            "name" => "Scienze dell'Amministazione Digitale",
            "cfu" => 180
        ]);
        
        GenerateSSD::createAll();
    }
}
