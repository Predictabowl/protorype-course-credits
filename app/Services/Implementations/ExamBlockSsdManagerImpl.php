<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Models\ExamBlock;
use App\Services\Interfaces\ExamBlockSsdManager;
use Exception;

/**
 * Description of ExamBlockSsdManagerImpl
 *
 * @author piero
 */
class ExamBlockSsdManagerImpl implements ExamBlockSsdManager{
    //put your code here
    public function addSsd(int $examBlockId, string $ssd): void {
        throw new Exception("Method not yet implemented");
    }

    public function eagerLoadExamBlock(int $examBlockId): ExamBlock {
        throw new Exception("Method not yet implemented");
    }

    public function removeSsd(int $examBlockId, int $ssdId): void {
        throw new Exception("Method not yet implemented");
    }

}
