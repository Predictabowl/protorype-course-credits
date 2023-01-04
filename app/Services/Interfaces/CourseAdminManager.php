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

    public function getCourseFullData($courseId): ?Course;
    public function saveExamBlock(NewExamBlockInfo $examBlock, $courseId): ExamBlock;
    public function updateExamBlock(ExamBlock $examBlock): bool;
    public function saveExam(NewExamInfo $exam, $examBlockId): Exam;
    public function updateExam(Exam $exam): bool;
    public function addExamOfChoice($examBlockId): bool;
}
