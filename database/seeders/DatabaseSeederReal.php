<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\ExamBlockOption;
use App\Models\ExamBlockOptionSsd;
use App\Models\RoleUser;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\ExamBlock;
use App\Models\Exam;
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
        $admin = User::create([
            "name" => "Amministratore Temporaneo",
            "email" => "admin@email.org",
            "password" => Hash::make("password"),
            "email_verified_at" => now()
        ]);
        $admin->email_verified_at = now();
        
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
        
        $course = Course::create([
            "name" => "Scienze dell'Amministazione Digitale",
            "cfu" => 180
        ]);
        
        GenerateSSD::createAll();
       
        $this->generateExams($course->id);
    }
        
    private function generateExams($courseId) {
        $this->generateBlock($courseId, 1, 
            [["Storia contemporanea (a distanza)",12,"M-STO/04"],
            ["Storia del pensiero politico",12,"SPS/02"]],
            ["M-STO/04", "SPS/02", "SPS/03", "SPS/04", "SPS/07"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Diritto privato (a distanza)",12,"IUS/01"]],
            ["IUS/01","IUS/09","IUS/10","IUS/14","IUS/21"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Istituzione di diritto pubblico (a distanza)",12,"IUS/09"]],
            ["IUS/01","IUS/09","IUS/10","IUS/14","IUS/21"]
        );

        $this->generateBlock($courseId, 1, [
            ["Diritto amministrativo (a distanza)",12,"IUS/10"]],
            ["IUS/01","IUS/09","IUS/10","IUS/14","IUS/21"]
        );
        
        $this->generateBlock($courseId, 2, [
            ["Informatica (a distanza)",12,"INF/01"],
            ["Statistica (a distanza)",12,"SECS-S/01"],
            ["Modellazione di processi amministrativi e compliance normativa (a distanza)",12,"INF/01"]],
            ["INF/01","SECS-P/01","SECS-P/02","SECS-P/03","SECS-S/01","SECS-S/03","SECS-S/05"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Lingua Francese (a distanza)",9,"L-LIN/04"],
            ["Lingua Inglese (a distanza)",9,"L-LIN/12"]],
            ["L-LIN/04","L-LIN/07","L-LIN/12","L-LIN/14"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Storia delle istituzione politiche e sociali (a distanza)",6,"SPS/03"],
            ["Diritti umani: storia (a distanza)",6,"SPS/03"]],
            ["SPS/01","SPS/03","SPS/04","SPS/11"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Analisi delle politiche pubbliche (a distanza)",6,"SPS/04"],
            ["Scienza politica (a distanza)",6,"SPS/04"]],
            ["SPS/01","SPS/03","SPS/04","SPS/11"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Sociologia (a distanza)",12,"SPS/07"]],
            ["M-PSI/05","M-PSI/06","SPS/07","SPS/09"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Economia industriale (a distanza)",6,"SECS-P/06"],
            ["Scienza delle finanze (a distanza)",6,"SECS-P/03"],
            ["Economia aziendale (a distanza)",6,"SECS-P/07"],
            ["Analisi dei dati, flussi di bilancio e big data (a distanza)",6,"SECS-P/08"]],
            ["SECS-P/01","SECS-P/02","SECS-P/03","SECS-P/06","SECS-P/07","SECS-P/08","SECS-P/10","SECS-S/04"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Economia politica (a distanza)",9,"SECS-P/01"]],
            ["SECS-P/01","SECS-P/02","SECS-P/03","SECS-P/06","SECS-P/07","SECS-P/08","SECS-P/10","SECS-S/04"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Diritto commerciale (a distanza)",9,"IUS/04"]],
            ["IUS/04","IUS/05","IUS/06","IUS/07","IUS/08","IUS/09","IUS/10","IUS/13","IUS/14","IUS/21"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Diritto sanitario (a distanza)",9,"IUS/10"]],
            ["IUS/04","IUS/05","IUS/06","IUS/07","IUS/08","IUS/09","IUS/10","IUS/13","IUS/14","IUS/21"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Diritto dell'Unione Europea (a distanza)",6,"IUS/14"],
            ["La cultura giuridica europea nel diritto pubblico (a distanza)",6,"IUS/21"],
            ["Big data e diritti fondamentali I (a distanza)",6,"IUS/08"],
            ["Diritto degli open data e trasparenza amministrativa (a distanza)",6,"IUS/10"],
            ["Diritto dei mercati agroalimentari I (a distanza)",6,"IUS/10"]],
            ["IUS/04","IUS/05","IUS/06","IUS/07","IUS/08","IUS/09","IUS/10","IUS/13","IUS/14","IUS/21"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Diritto del lavoro (a distanza)",9,"IUS/07"],
            ["Diritto dell'immigrazione (a distanza)",9,"IUS/10"]], // Ricontrollare!!!! Questo esame ha 2 IUS con CFU diversi
            ["INF/01","IUS/01","IUS/05","IUS/07","IUS/10","IUS/12","IUS/14","IUS/16","IUS/17","M-STO/02","M-STO/04","SECS-S/04","SPS/02","SPS/03","SPS/04","SPS/08","SPS/09","SPS/12"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Diritto dei consumatori (a distanza)",6,"IUS/01"],
            ["Diritto della devianza (a distanza) / Sociologia del diritto (a distanza)",6,"SPS/12"],
            ["Diritto della previdenza sociale (a distanza)",6,"IUS/07"],
            ["Diritto dei beni culturali (a distanza)",6,"IUS/10"],
            ["Big data e diritti fondamentali II (a distanza)",6,"IUS/01"],
            ["Diritto dei mercati agroalimentari II (a distanza)",6,"IUS/01"],
            ["Sociologia dell'organizzazione e dell'innovazione digitale",6,"IUS/09"],
            ["Diritto sanitario II (a distanza)",6,"IUS/10"]],
            ["INF/01","IUS/01","IUS/05","IUS/07","IUS/10","IUS/12","IUS/14","IUS/16","IUS/17","M-STO/02","M-STO/04","SECS-S/04","SPS/02","SPS/03","SPS/04","SPS/08","SPS/09","SPS/12"]
        );
        
        $this->generateBlock($courseId, 1, [
            ["Istituzioni di diritto e procedura penale (a distanza)",9,"IUS/07"]],
            ["INF/01","IUS/01","IUS/05","IUS/07","IUS/10","IUS/12","IUS/14","IUS/16","IUS/17","M-STO/02","M-STO/04","SECS-S/04","SPS/02","SPS/03","SPS/04","SPS/08","SPS/09","SPS/12"]
        );
        
        $this->generateFreeChoiceExams($courseId, 2, 6);
        
    }
    
    private function generateBlock($courseId, $maxExams, $data, $compatibilities = []){
        $block = ExamBlock::create([
            "max_exams" => $maxExams,
            "course_id" => $courseId
        ]);
        
        foreach ($data as $value) {
            $exam = Exam::create([
                "name" => $value[0],
                "cfu" => $value[1],
                "ssd_id" => GenerateSSD::getSSDId($value[2])
            ]);
            
            $option = ExamBlockOption::create([
                "exam_id" => $exam->id,
                "exam_block_id" => $block->id
            ]);
            
            foreach ($compatibilities as $compatibility) {
                ExamBlockOptionSsd::create([
                    "ssd_id" => GenerateSSD::getSSDId($compatibility),
                    "exam_block_option_id" => $option->id
                ]);
            }
            
        }

    }
    
    private function generateFreeChoiceExams($courseId, $numExams, $cfu){
        $exam = Exam::create([
                "name" => "Esame a scelta dello studente",
                "cfu" => $cfu,
        ]);
        
        for ($index = 0; $index < $numExams; $index++) {
            $block = ExamBlock::create([
                "max_exams" => 1,
                "course_id" => $courseId
            ]);

            $option = ExamBlockOption::create([
                    "exam_id" => $exam->id,
                    "exam_block_id" => $block->id
            ]);
        }
    }
}
