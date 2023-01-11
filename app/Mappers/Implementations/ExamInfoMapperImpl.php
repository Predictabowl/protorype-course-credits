<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Mappers\Implementations;

use App\Domain\NewExamInfo;
use App\Mappers\Interfaces\ExamInfoMapper;
use App\Models\Exam;

/**
 * Description of ExamInfoMapperImpl
 *
 * @author piero
 */
class ExamInfoMapperImpl implements ExamInfoMapper{
    public function map(NewExamInfo $examInfo, int $examBlockId, ?int $ssdId): Exam {
        return new Exam([
            "name" => $examInfo->getName(),
            "exam_block_id" => $examBlockId,
            "ssd_id" => $ssdId,
            "free_choice" => $examInfo->isFreeChoice()
        ]);
    }

}
