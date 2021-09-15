<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Front;
use App\Models\TakenExam;
use Illuminate\Support\Facades\Hash;
use App\Support\Seeders\GenerateSSD;

class DatabaseSeederUserExample extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            "name" => "Nome Test",
            "email" => "nome@email.org",
            "password" => Hash::make("password"),
            "email_verified_at" => now()
        ]);
        
       $front = Front::create([
           "user_id" => $user->id,
       ]);
       
        $data = [
            ["Analisi delle politiche pubbliche 1","SPS/04",5,24],  
            ["Scienza Politica mod1","SPS/04",5,21],
            ["Scienza Politica mod.2","SPS/04",5,21],
            ["Diritto Amministrativo mod.1","IUS/10",5,22],
            ["Diritto Amministrativo mod.2","IUS/10",5,22],
            ["Gestione delle Imprese profit e no profit","SECS-P/08",5,24],
            ["Diritto Commerciale mod.1","IUS/04",5,29],
            ["Diritto Commerciale mod.2","IUS/04",5,29],
            ["Scienza delle Finanze mod.1","SECS-P/03",5,25],
            ["Scienza delle Finanze mod.2","SECS-P/03",5,25],
            ["Sociologia Generale mod.1","SPS/07",5,23],
            ["Sociologia Generale mod.2","SPS/07",5,23],
            ["Istituzioni di Diritto pubblico mod.1","IUS/09",5,25],
            ["Istituzioni di Diritto pubblico mod.2","IUS/09",5,25],
            ["Diritto dell'unione Europea","IUS/14",5,20],
            ["Storia delle istituzione politiche mod.1","SPS/03",5,18],
            ["Storia delle istituzione politiche mod.2","SPS/03",5,24],
            ["Istituzioni di Economia mod.1","SECS-P/01",5,19],
            ["Istituzioni di Economia mod.2","SECS-P/01",5,19],
       ];
       
       $this->createEntries($data, $front->id);
    }
    
    private function createEntries($data, $frontId){
        foreach($data as $value){
            $this->createEntry($value[0], $value[1], $value[2], $value[3], $frontId);
        }
    }
    
    private function createEntry($name, $ssd, $cfu, $grade, $frontId){
        TakenExam::create([
            "name" => $name,
            "cfu" => $cfu,
            "ssd_id" => GenerateSSD::getSSDId($ssd),
            "grade" => $grade,
            "front_id" => $frontId
        ]);
    }
}
