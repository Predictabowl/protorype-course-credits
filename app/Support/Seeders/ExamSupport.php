<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Support\Seeders;

use App\Models\Exam;

/**
 * Description of ExamSupport
 *
 * @author piero
 */
class ExamSupport {
    
    public const FREE_CHOICE_NAME = "Esame a scelta dello studente";
    
    public static function getFreeChoiceExam(): Exam{
        return Exam::firstOrCreate([
                "name" => ExamSupport::FREE_CHOICE_NAME,
        ]);
    }
    
    public static function findFreeChoiceExam(): ?Exam{
        $exam = Exam::where("name","=",ExamSupport::FREE_CHOICE_NAME)
                ->whereNull("ssd_id")->get()->first();
        return $exam;
    }
    
    public static function isFreeChoiceExam($examId): bool{
        $exam = ExamSupport::findFreeChoiceExam();
        if(is_null($exam)){
            return false;
        }
        if($exam->id == $examId){
            return true;
        }
        return false;
    }
}
