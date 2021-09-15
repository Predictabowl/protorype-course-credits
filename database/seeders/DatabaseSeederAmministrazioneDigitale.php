<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Support\Seeders\GenerateExamBlock;

class DatabaseSeederAmministrazioneDigitale extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $course = Course::create([
            "name" => "Scienze dell'Amministazione",
            "cfu" => 180,
            "maxRecognizedCfu" => 120,
            "finalExamCfu" => 6,
            "otherActivitiesCfu" => 6
        ]);
        
        $this->generateExams($course->id);
    }
        
    private function generateExams($courseId) {
        GenerateExamBlock::generateBlock($courseId, 1, 12, 1,
            [["Storia contemporanea (a distanza)","M-STO/04"],
            ["Storia del pensiero politico","SPS/02"]],
            ["M-STO/04", "SPS/02", "SPS/03", "SPS/04", "SPS/07"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 12, 1,[
            ["Diritto privato (a distanza)","IUS/01"]],
            ["IUS/01","IUS/09","IUS/10","IUS/14","IUS/21"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 12, 1, [
            ["Istituzioni di diritto pubblico (a distanza)","IUS/09"]],
            ["IUS/01","IUS/09","IUS/10","IUS/14","IUS/21"]
        );

        GenerateExamBlock::generateBlock($courseId, 1, 12, 2, [
            ["Diritto amministrativo (a distanza)","IUS/10"]],
            ["IUS/01","IUS/09","IUS/10","IUS/14","IUS/21"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 2, 6, 2, [
            ["Informatica (a distanza)","INF/01"],
            ["Statistica (a distanza)","SECS-S/01"],
            ["Modellazione di processi amministrativi e compliance normativa (a distanza)","INF/01"]],
            ["INF/01","SECS-P/01","SECS-P/02","SECS-P/03","SECS-S/01","SECS-S/03","SECS-S/05"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, 2, [
            ["Lingua Francese (a distanza)","L-LIN/04"],
            ["Lingua Inglese (a distanza)","L-LIN/12"]],
            ["L-LIN/04","L-LIN/07","L-LIN/12","L-LIN/14"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 6, 3, [
            ["Storia delle istituzione politiche e sociali (a distanza)","SPS/03"],
            ["Diritti umani: storia (a distanza)","SPS/03"]],
            ["SPS/01","SPS/03","SPS/04","SPS/11"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 6, 3, [
            ["Analisi delle politiche pubbliche (a distanza)","SPS/04"],
            ["Scienza politica (a distanza)","SPS/04"]],
            ["SPS/01","SPS/03","SPS/04","SPS/11"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 12, 1, [
            ["Sociologia (a distanza)","SPS/07"]],
            ["M-PSI/05","M-PSI/06","SPS/07","SPS/09"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 6, 3, [
            ["Economia industriale (a distanza)","SECS-P/06"],
            ["Scienza delle finanze (a distanza)","SECS-P/03"],
            ["Economia aziendale (a distanza)","SECS-P/07"],
            ["Analisi dei dati, flussi di bilancio e big data (a distanza)","SECS-P/08"]],
            ["SECS-P/01","SECS-P/02","SECS-P/03","SECS-P/06","SECS-P/07","SECS-P/08","SECS-P/10","SECS-S/04"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, 1, [
            ["Economia politica (a distanza)","SECS-P/01"]],
            ["SECS-P/01","SECS-P/02","SECS-P/03","SECS-P/06","SECS-P/07","SECS-P/08","SECS-P/10","SECS-S/04"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, 2, [
            ["Diritto commerciale (a distanza)","IUS/04"]],
            ["IUS/04","IUS/05","IUS/06","IUS/07","IUS/08","IUS/09","IUS/10","IUS/13","IUS/14","IUS/21"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, 2, [
            ["Diritto sanitario (a distanza)","IUS/10"]],
            ["IUS/04","IUS/05","IUS/06","IUS/07","IUS/08","IUS/09","IUS/10","IUS/13","IUS/14","IUS/21"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 6, 2, [
            ["Diritto dell'Unione Europea (a distanza)","IUS/14"],
            ["La cultura giuridica europea nel diritto pubblico (a distanza)","IUS/21"],
            ["Big data e diritti fondamentali I (a distanza)","IUS/08"],
            ["Diritto degli open data e trasparenza amministrativa (a distanza)","IUS/10"],
            ["Diritto dei mercati agroalimentari I (a distanza)","IUS/10"]],
            ["IUS/04","IUS/05","IUS/06","IUS/07","IUS/08","IUS/09","IUS/10","IUS/13","IUS/14","IUS/21"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, 2, [
            ["Diritto del lavoro (a distanza)","IUS/07"],
            ["Diritto dell'immigrazione (a distanza)","IUS/10"]], // Ricontrollare!!!! Questo esame ha 2 IUS con CFU diversi
            ["INF/01","IUS/01","IUS/05","IUS/07","IUS/10","IUS/12","IUS/14","IUS/16","IUS/17","M-STO/02","M-STO/04","SECS-S/04","SPS/02","SPS/03","SPS/04","SPS/08","SPS/09","SPS/12"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 6, 3, [
            ["Diritto dei consumatori (a distanza)","IUS/01"],
            ["Diritto della devianza (a distanza) / Sociologia del diritto (a distanza)","SPS/12"],
            ["Diritto della previdenza sociale (a distanza)","IUS/07"],
            ["Diritto dei beni culturali (a distanza)","IUS/10"],
            ["Big data e diritti fondamentali II (a distanza)","IUS/01"],
            ["Diritto dei mercati agroalimentari II (a distanza)","IUS/01"],
            ["Sociologia dell'organizzazione e dell'innovazione digitale","IUS/09"],
            ["Diritto sanitario II (a distanza)","IUS/10"]],
            ["INF/01","IUS/01","IUS/05","IUS/07","IUS/10","IUS/12","IUS/14","IUS/16","IUS/17","M-STO/02","M-STO/04","SECS-S/04","SPS/02","SPS/03","SPS/04","SPS/08","SPS/09","SPS/12"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, 3, [
            ["Istituzioni di diritto e procedura penale (a distanza)","IUS/07"]],
            ["INF/01","IUS/01","IUS/05","IUS/07","IUS/10","IUS/12","IUS/14","IUS/16","IUS/17","M-STO/02","M-STO/04","SECS-S/04","SPS/02","SPS/03","SPS/04","SPS/08","SPS/09","SPS/12"]
        );
        
        GenerateExamBlock::generateFreeChoiceExams($courseId, 2, 6);
        
    }
}
