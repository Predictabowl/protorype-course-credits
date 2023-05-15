<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Services\Interfaces;

use App\Domain\NewExamInfo;
use App\Models\Exam;

/**
 *
 * @author piero
 */
interface ExamManager {
    public function saveExam(NewExamInfo $exam, int $examBlockId): Exam;
    public function updateExam(NewExamInfo $exam, int $examId): Exam;
    public function deleteExam(int $examId): void;
}
