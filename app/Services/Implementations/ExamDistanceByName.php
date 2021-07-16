<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\ExamDistance;
use App\Domain\Interfaces\ExamDTO;

/**
 * Only consider the name of the exams
 * Low precision - high performance (n*m) implementation
 */

class ExamDistanceByName implements ExamDistance {

    /**
     * The function assumes that the names are already trimmed.
     * 
     * @param ExamDTO $exam1
     * @param ExamDTO $exam2
     * @return int distance
     */
    public function calculateDistance(ExamDTO $exam1, ExamDTO $exam2): int {
        return levenshtein(strtolower($exam1->getExamName()),
                strtolower($exam2->getExamName()), 1, 1, 1);
    }
}
