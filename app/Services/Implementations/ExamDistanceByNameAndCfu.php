<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\ExamDistance;
use App\Domain\Interfaces\ExamDTO;

/**
 * Considers the name of the exams and the cfu difference
 * don't know if it makes any sense.
 */

class ExamDistanceByNameAndCfu implements ExamDistance {

    /**
     * The function assumes that the names are already trimmed.
     * 
     * @param ExamDTO $exam1
     * @param ExamDTO $exam2
     * @return int distance
     */
    public function calculateDistance(ExamDTO $exam1, ExamDTO $exam2): int {
        $distance = levenshtein(strtolower($exam1->getExamName()),
                strtolower($exam2->getExamName()), 1, 1, 1);
        $distance += abs($exam1->getCfu() - $exam2->getCfu());
        return $distance;
    }
}
