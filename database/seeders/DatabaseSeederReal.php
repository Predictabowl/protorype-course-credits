<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\ExamBlockOption;
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
        
        $course = Course::create([
            "name" => "Scienze dell'Amministazione Digitale",
            "cfu" => 180
        ]);
        
        GenerateSSD::createAll();
       
        $this->generateExams($course->id);
    }
        
    private function generateExams($courseId) {
        $this->generateBlock($courseId, 1, [
            ["Storia contemporanea (a distanza)",12,"M-STO/04"],
            ["Storia del pensiero politico",12,"SPS/02"],
        ]);
        
        $this->generateBlock($courseId, 1, [
            ["Diritto privato (a distanza)",12,"IUS/01"]
        ]);
        
        $this->generateBlock($courseId, 1, [
            ["Istituzione di diritto pubblico (a distanza)",12,"IUS/09"]
        ]);

        $this->generateBlock($courseId, 1, [
            ["Diritto amministrativo (a distanza)",12,"IUS/10"]
        ]);
        
        $this->generateBlock($courseId, 2, [
            ["Informatica (a distanza)",12,"INF/01"],
            ["Statistica (a distanza)",12,"SECS-S/01"],
            ["Modellazione di processi amministrativi e compliance normativa (a distanza)",12,"INF/01"],
        ]);
        
        $this->generateBlock($courseId, 1, [
            ["Lingua Francese (a distanza)",9,"L-LIN/04"],
            ["Lingua Inglese (a distanza)",9,"L-LIN/12"],
        ]);
        
        $this->generateBlock($courseId, 1, [
            ["Storia delle istituzione politiche e sociali (a distanza)",6,"SPS/03"],
            ["Diritti umani: storia (a distanza)",6,"SPS/03"],
        ]);
        
        $this->generateBlock($courseId, 1, [
            ["Analisi delle politiche pubbliche (a distanza)",6,"SPS/04"],
            ["Scienza politica (a distanza)",6,"SPS/04"],
        ]);
        
        $this->generateBlock($courseId, 1, [
            ["Sociologia (a distanza)",12,"SPS/07"],
        ]);
        
        $this->generateBlock($courseId, 1, [
            ["Economia industriale (a distanza)",6,"SECS-P/06"],
            ["Scienza delle finanze (a distanza)",6,"SECS-P/03"],
            ["Economia aziendale (a distanza)",6,"SECS-P/07"],
            ["Analisi dei dati, flussi di bilancio e big data (a distanza)",6,"SECS-P/08"],
        ]);
        
        $this->generateBlock($courseId, 1, [
            ["Economia politica (a distanza)",9,"SECS-P/01"],
        ]);
        
        $this->generateBlock($courseId, 1, [
            ["Diritto commerciale (a distanza)",9,"IUS/04"],
        ]);
        
        $this->generateBlock($courseId, 1, [
            ["Diritto sanitario (a distanza)",9,"IUS/10"],
        ]);
        
        $this->generateBlock($courseId, 1, [
            ["Diritto dell'Unione Europea (a distanza)",6,"IUS/14"],
            ["La cultura giuridica europea nel diritto pubblico (a distanza)",6,"IUS/21"],
            ["Big data e diritti fondamentali (a distanza)",6,"IUS/08"],
            ["Diritto degli open data e trasparenza amministrativa (a distanza)",6,"IUS/10"],
            ["Diritto dei mercati agroalimentari (a distanza)",6,"IUS/10"],
        ]);
    }
    
    private function generateBlock($courseId, $maxExams, $data){
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
            
            ExamBlockOption::create([
                "exam_id" => $exam->id,
                "exam_block_id" => $block->id
            ]);
            
        }

    }
}
