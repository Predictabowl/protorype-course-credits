<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Ssd;
use Database\Seeders\Generators\GenerateSSD;

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
            "name" => "admin",
            "email" => "admin@email.com",
            "role" => "admin",
            "password" => Hash::make("password")
        ]);
        
        $course = Course::factory()->create([
            "name" => "Scienze dell'Amministazione Digitale",
            "cfu" => 180
        ]);
        
        GenerateSSD::createAll();
    }
}
