<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\ExamDistance;
use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;

class ExamDistanceByName implements ExamDistance {

    public function calculateDistance(ExamOptionDTO $option, TakenExamDTO $taken): int {
        return levenshtein($option->getExamName(),
                $taken->getExamName(), 1, 2, 1);
    }
}
