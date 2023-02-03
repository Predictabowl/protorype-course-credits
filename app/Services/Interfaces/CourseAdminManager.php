<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Services\Interfaces;

use App\Domain\NewExamBlockInfo;
use App\Domain\NewExamInfo;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;

/**
 *
 * @author piero
 */
interface CourseAdminManager {

    public function getCourseFullData(int $courseId): ?Course;
    public function saveExamBlock(NewExamBlockInfo $examBlock, int $courseId): ExamBlock;
    public function updateExamBlock(NewExamBlockInfo $examBlock, int $examBlockId): void;
    public function deleteExamBlock(int $examBlockId): void;
    public function saveExam(NewExamInfo $exam, int $examBlockId): Exam;
    public function updateExam(NewExamInfo $exam, int $examId): Exam;
    public function deleteExam(int $examId): void;
}
