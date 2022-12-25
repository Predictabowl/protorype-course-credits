<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Services\Interfaces;

use App\Models\Exam;
use App\Models\ExamBlock;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface CourseAdminManager {
    
    public function getAll(): Collection;
    public function saveExamBlock(ExamBlock $examBlock): bool;
    public function saveExam(Exam $exam, $examBlockId): bool;
}
