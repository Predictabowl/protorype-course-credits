<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Services\Interfaces;

use App\Domain\NewExamBlockInfo;
use App\Domain\NewExamInfo;
use App\Models\Course;

/**
 *
 * @author piero
 */
interface CourseAdminManager {

    public function getCourseFullData(int $courseId): ?Course;
    public function saveExamBlock(NewExamBlockInfo $examBlock, int $courseId): void;
    public function updateExamBlock(NewExamBlockInfo $examBlock, int $examBlockId): bool;
    public function deleteExamBlock(int $examBlockId): bool;
    public function saveExam(NewExamInfo $exam, int $examBlockId): void;
    public function updateExam(NewExamInfo $exam, int $examId): bool;
    public function deleteExam(int $examId): bool;
}
