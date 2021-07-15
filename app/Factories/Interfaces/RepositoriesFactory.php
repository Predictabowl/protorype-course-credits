<?php

namespace App\Factories\Interfaces;

use App\Repositories\Interfaces\TakenExamRepository;
use App\Repositories\Interfaces\ExamBlockRepository;

/**
 *
 * @author piero
 */
interface RepositoriesFactory {
    
    public function getTakenExamRepository(): TakenExamRepository;
    
    public function getExamBlockRepository(): ExamBlockRepository;
}
