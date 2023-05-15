<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Mappers\Interfaces;

use App\Domain\NewExamBlockInfo;
use App\Models\ExamBlock;

/**
 *
 * @author piero
 */
interface ExamBlockInfoMapper {

    public function map(NewExamBlockInfo $examBlock, ?int $courseId): ExamBlock;
}
