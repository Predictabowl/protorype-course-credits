<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Ssd;

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
        $inf01 = Ssd::factory()->create([
           "code" => "INF/01"
        ]);
        $ius01 = Ssd::factory()->create([
           "code" => "IUS/01"
        ]);
        $ius05 = Ssd::factory()->create([
           "code" => "IUS/05"
        ]);
        $ius07 = Ssd::factory()->create([
           "code" => "IUS/07"
        ]);
        $ius09 = Ssd::factory()->create([
           "code" => "IUS/09"
        ]);
        $ius10 = Ssd::factory()->create([
           "code" => "IUS/10"
        ]);
        $ius12 = Ssd::factory()->create([
           "code" => "IUS/12"
        ]);
        $ius14 = Ssd::factory()->create([
           "code" => "IUS/14"
        ]);
        $ius16 = Ssd::factory()->create([
           "code" => "IUS/16"
        ]);
        $ius17 = Ssd::factory()->create([
           "code" => "IUS/17"
        ]);
        $ius21 = Ssd::factory()->create([
           "code" => "IUS/21"
        ]);
        $lin04 = Ssd::factory()->create([
           "code" => "L-LIN/04"
        ]);
        $lin07 = Ssd::factory()->create([
           "code" => "L-LIN/07"
        ]);
        $lin12 = Ssd::factory()->create([
           "code" => "L-LIN/12"
        ]);
        $lin14 = Ssd::factory()->create([
           "code" => "L-LIN/14"
        ]);
        $mpsi05 = Ssd::factory()->create([
           "code" => "M-PSI/05"
        ]);
        $mpsi06 = Ssd::factory()->create([
           "code" => "M-PSI/06"
        ]);
        $msto02 = Ssd::factory()->create([
           "code" => "M-STO/02"
        ]);
        $msto04 = Ssd::factory()->create([
           "code" => "M-STO/04"
        ]);
        $secsp01 = Ssd::factory()->create([
           "code" => "SECS-P/01"
        ]);
        $secsp02 = Ssd::factory()->create([
           "code" => "SECS-P/02"
        ]);
        $secsp03 = Ssd::factory()->create([
           "code" => "SECS-P/03"
        ]);
        $secsp04 = Ssd::factory()->create([
           "code" => "SECS-P/04"
        ]);
        $secsp06 = Ssd::factory()->create([
           "code" => "SECS-P/06"
        ]);
        $secsp07 = Ssd::factory()->create([
           "code" => "SECS-P/07"
        ]);
        $secsp08 = Ssd::factory()->create([
           "code" => "SECS-P/08"
        ]);
        $secsp10 = Ssd::factory()->create([
           "code" => "SECS-P/10"
        ]);
        $secss04 = Ssd::factory()->create([
           "code" => "SECS-S/04"
        ]);
        $sps01 = Ssd::factory()->create([
           "code" => "SPS/01"
        ]);
        $sps02 = Ssd::factory()->create([
           "code" => "SPS/02"
        ]);
        $sps03 = Ssd::factory()->create([
           "code" => "SPS/03"
        ]);
        $sps04 = Ssd::factory()->create([
           "code" => "SPS/04"
        ]);
        $sps07 = Ssd::factory()->create([
           "code" => "SPS/07"
        ]);
        $sps08 = Ssd::factory()->create([
           "code" => "SPS/08"
        ]);
        $sps09 = Ssd::factory()->create([
           "code" => "SPS/09"
        ]);
        $sps11 = Ssd::factory()->create([
           "code" => "SPS/11"
        ]);
        $sps12 = Ssd::factory()->create([
           "code" => "SPS/12"
        ]);
        
    }
}
