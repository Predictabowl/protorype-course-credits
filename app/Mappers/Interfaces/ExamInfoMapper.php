<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Mappers\Interfaces;

use App\Domain\NewExamInfo;
use App\Models\Exam;

/**
 *
 * @author piero
 */
interface ExamInfoMapper {

    public function map(NewExamInfo $examInfo, int $examBlockId, ?int $ssdId): Exam;
}
