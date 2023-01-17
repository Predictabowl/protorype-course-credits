<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Mappers\Implementations;

use App\Domain\NewExamBlockInfo;
use App\Mappers\Interfaces\ExamBlockInfoMapper;
use App\Models\ExamBlock;

/**
 * Description of ExamBlockInfoMapperImpl
 *
 * @author piero
 */
class ExamBlockInfoMapperImpl implements ExamBlockInfoMapper{
    public function map(NewExamBlockInfo $examBlock, ?int $courseId): ExamBlock {
        return new ExamBlock([
            "max_exams" => $examBlock->getMaxExams(),
            "cfu" => $examBlock->getCfu(),
            "course_year" => $examBlock->getCourseYear(),
            "course_id" => $courseId
        ]);
    }

}
