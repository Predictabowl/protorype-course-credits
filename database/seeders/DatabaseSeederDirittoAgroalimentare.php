<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Support\Seeders\GenerateExamBlock;

class DatabaseSeederDirittoAgroalimentare extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $course = Course::create([
            "name" => "Diritto Agroalimentare",
            "cfu" => 180,
            "maxRecognizedCfu" => 120,
            "finalExamCfu" => 6,
            "otherActivitiesCfu" => 9,
            "numberOfYears" => 3,
            "cfuTresholdForYear" => 40
        ]);
        
        $this->generateExams($course->id);
    }
        
    private function generateExams($courseId) {
        GenerateExamBlock::generateBlock($courseId, 1, 9, null,
            [["La proprietà fondiaria in diritto romano (a distanza)","IUS/18"]],
            []
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, null,[
            ["Storia del diritto (a distanza)","IUS/19"]],
            []
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, null, [
            ["Alimentazione e cultura giuridica (a distanza)","IUS/20"]],
            []
        );

        GenerateExamBlock::generateBlock($courseId, 1, 12, null, [
            ["Diritto privato (a distanza)","IUS/01"]],
            []
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 12, null, [
            ["Diritto costituzionale (a distanza)","IUS/08"]],
            []
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, null, [
            ["Economia politica (a distanza)","IUS/12"]],
            ["IUS/12","SECS-P/01","SECS-P/03","SECS-S/01"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, null, [
            ["Diritto dell'U.E. e Politica agricola (a distanza)","IUS/04"]],
            ["IUS/04", "IUS/07", "IUS/10", "IUS/13", "IUS/14", "IUS/17"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, null, [
            ["Diritto penale I (a distanza)","IUS/07"]],
            ["IUS/04", "IUS/07", "IUS/10", "IUS/13", "IUS/14", "IUS/17"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, null, [
            ["Diritto commerciale (a distanza)","IUS/10"]],
            ["IUS/04", "IUS/07", "IUS/10", "IUS/13", "IUS/14", "IUS/17"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 12, null, [
            ["Diritto amministrativo (a distanza)","IUS/10"]],
            ["IUS/04", "IUS/07", "IUS/10", "IUS/13", "IUS/14", "IUS/17"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, null, [
            ["Diritto internazionale (a distanza)","IUS/14"]],
            ["IUS/04", "IUS/07", "IUS/10", "IUS/13", "IUS/14", "IUS/17"]
        );

        GenerateExamBlock::generateBlock($courseId, 1, 9, null, [
            ["Comparative food law (a distanza)","IUS/02"]],
            ["IUS/02", "IUS/03", "IUS/05", "IUS/15", "IUS/16", "IUS/21"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, null, [
            ["Diritto agrario (a distanza)","IUS/03"]],
            ["IUS/02", "IUS/03", "IUS/05", "IUS/15", "IUS/16", "IUS/21"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 9, null, [
            ["Diritto dell'economia degli alimenti (a distanza)","IUS/05"]],
            ["IUS/02", "IUS/03", "IUS/05", "IUS/15", "IUS/16", "IUS/21"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 6, null, [
            ["Diritto civile dell'agricoltura (a distanza)","IUS/01"]],
            ["IUS/01", "IUS/02", "IUS/03", "IUS/04", "IUS/05", "IUS/07"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 6, null, [
            ["Diritto costituzionale II (a distanza)","IUS/08"]],
            ["IUS/08", "IUS/09", "IUS/10", "IUS/11", "IUS/14", "IUS/07", "IUS/21"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 6, null, [
            ["Diritto vitinvinicolo (a distanza)","IUS/09"]],
            ["IUS/08", "IUS/09", "IUS/10", "IUS/11", "IUS/14", "IUS/07", "IUS/21"]
        );
        
        GenerateExamBlock::generateBlock($courseId, 1, 6, null, [
            ["Diritto dei mercati agroalimentari I (a distanza)","IUS/03"],
            ["Diritto dei mercati agroalimentari II (a distanza)","IUS/03"],
            ["Diritto amministrativo europeo dell'ambiente (a distanza)","IUS/14"],
            ["Diritto dei consumatori (a distanza)","IUS/04"],
            ["Diritto costituzionale II: diritto costituzionale dell'ambiente e del paesaggio (a distanza)","IUS/08"]],
            ["IUS/03", "IUS/14", "IUS/04", "IUS/08"]
        );
        
        GenerateExamBlock::generateFreeChoiceExams($courseId, 1, 6);
        
    }
}
