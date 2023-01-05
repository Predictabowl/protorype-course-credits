<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Support\Seeders;

use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\ExamBlockSsd;

/**
 * Description of GenerateExamBlock
 *
 * @author piero
 */
class GenerateExamBlock {
    

    public static function generateBlock(
            $courseId,
            int $maxExams,
            int $cfu,
            ?int $year,
            $data,
            $compatibilities = [])
    {
        $block = ExamBlock::create([
            "max_exams" => $maxExams,
            "course_id" => $courseId,
            "cfu" => $cfu,
            "courseYear" => $year
        ]);
        
        foreach ($compatibilities as $compatibility) {
            ExamBlockSsd::create([
                "ssd_id" => GenerateSSD::getSSDId($compatibility),
                "exam_block_id" => $block->id
            ]);
        }
        
//        foreach ($data as $value) {
//            $exam = Exam::firstOrCreate([
//                "name" => $value[0],
//                "ssd_id" => GenerateSSD::getSSDId($value[1]),
//                "exam_block_id" => $block->id
//            ]);
        foreach ($data as $value) {
            $exam = Exam::create([
                "name" => $value[0],
                "ssd_id" => GenerateSSD::getSSDId($value[1]),
                "exam_block_id" => $block->id
            ]);
            
        }

    }
    
    public static function generateFreeChoiceExams($courseId, $numExams, $cfu){
//        $exam = ExamSupport::getFreeChoiceExam();
        
        for ($index = 0; $index < $numExams; $index++) {
            $block = ExamBlock::create([
                "max_exams" => 1,
                "course_id" => $courseId,
                "cfu" => $cfu
            ]);
            
            Exam::Create([
                "name" => ExamSupport::FREE_CHOICE_NAME,
                "free_choice" => true,
                "exam_block_id" => $block->id
            ]);

//            $option = ExamBlockOption::create([
//                    "exam_id" => $exam->id,
//                    "exam_block_id" => $block->id
//            ]);
        }
    }
}
