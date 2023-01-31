<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Services\Interfaces;

use App\Models\ExamBlock;

/**
 *
 * @author piero
 */
interface ExamBlockSsdManager {
    public function addSsd(int $examBlockId, string $ssd): void;
    public function removeSsd(int $examBlockId, int $ssdId): void;
    public function eagerLoadExamBlock(int $examBlockId): ExamBlock;
}
