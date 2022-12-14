<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Repositories\Interfaces;

use App\Models\Exam;

/**
 *
 * @author piero
 */
interface ExamRepository {
    public function get($id): ?Exam;
    public function save(Exam $exam): bool;
    public function update(Exam $exam): bool;
}
