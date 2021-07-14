<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\ExamDistance;
use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;

class ExamDistanceByName implements ExamDistance {

    /**
     * The function assumes that the names are already trimmed.
     * 
     * @param ExamOptionDTO $option
     * @param TakenExamDTO $taken
     * @return int distance
     */
    public function calculateDistance(ExamOptionDTO $option, TakenExamDTO $taken): int {
        return levenshtein(strtolower($option->getExamName()),
                strtolower($taken->getExamName()), 1, 1, 1);
    }
}
