<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Services\Interfaces;

use App\Domain\NewExamBlockInfo;
use App\Domain\SsdCode;
use App\Models\ExamBlock;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface ExamBlockManager {
    
    public function saveExamBlock(NewExamBlockInfo $examBlock, int $courseId): ExamBlock;
    public function updateExamBlock(NewExamBlockInfo $examBlock, int $examBlockId): ExamBlock;
    public function deleteExamBlock(int $examBlockId): void;
    public function addSsd(int $examBlockId, SsdCode $ssdCode): void;
    public function getAllSsds(): Collection;
    public function removeSsd(int $examBlockId, int $ssdId): void;
    public function getExamBlockWithSsds(int $examBlockId): ExamBlock;
}
