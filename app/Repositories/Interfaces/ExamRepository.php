<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Repositories\Interfaces;

use App\Models\Exam;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface ExamRepository {
    public function get($id): ?Exam;
    public function save(Exam $exam): Exam;
    public function update(Exam $exam): void;
    public function delete(int $id): void;
    public function deleteBatch(Collection $ids): void;
}
