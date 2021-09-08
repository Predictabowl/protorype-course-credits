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
            ["Analisi delle politiche pubbliche 1","SPS/04",5],  
            ["Informatica di base mod. 3","INF/01",3],
            ["Scienza Politica mod1","SPS/04",5],
            ["Scienza Politica mod.2","SPS/04",5],
            ["Diritto Amministrativo mod.1","IUS/10",5],
            ["Diritto Amministrativo mod.2","IUS/10",5],
            ["Gestione delle Imprese profit e no profit","SECS-P/08",5],
            ["Diritto Commerciale mod.1","IUS/04",5],
            ["Diritto Commerciale mod.2","IUS/04",5],
            ["Scienza delle Finanze mod.1","SECS-P/03",5],
            ["Scienza delle Finanze mod.2","SECS-P/03",5],
            ["Sociologia Generale mod.1","SPS/07",5],
            ["Sociologia Generale mod.2","SPS/07",5],
            ["Istituzioni di Diritto pubblico mod.1","IUS/09",5],
            ["Istituzioni di Diritto pubblico mod.2","IUS/09",5],
            ["Diritto dell'unione Europea","IUS/14",5],
            ["Storia delle istituzione politiche mod.1","SPS/03",5],
            ["Storia delle istituzione politiche mod.2","SPS/03",5],
            ["Istituzioni di Economia mod.1","SECS-P/01",5],
            ["Istituzioni di Economia mod.2","SECS-P/01",5],
            ["Istituzioni di Diritto privato mod.A","IUS/01",9],
            ["Istituzioni di Diritto privato mod.B (Proc. Civile)","IUS/01",1],
            ["Lingua Inglese mod.1","L-LIN/12",5],
            ["Lingua Inglese mod.2","L-LIN/12",5],
            ["Sociologia dell'organizzazione","SPS/09",7],
            ["Sociologia dei processi culturali e comun. mod.1","SPS/08",8],
            ["Psicologia Organizzazione","M-PSI/06",3],
            ["Diritto Europero degli appalti pubblici mod.1 Corso a distanza","IUS/05",5],
            ["Diritto Europero appalti pubblici mod.2 Corso a distanza","IUS/05",5],
       ];
       
       $this->createEntries($data, $front->id);
    }
    
    private function createEntries($data, $frontId){
        foreach($data as $value){
            $this->createEntry($value[0], $value[1], $value[2], $frontId);
        }
    }
    
    private function createEntry($name, $ssd, $cfu, $frontId){
        TakenExam::create([
            "name" => $name,
            "cfu" => $cfu,
            "ssd_id" => GenerateSSD::getSSDId($ssd),
            "front_id" => $frontId
        ]);
    }
}
