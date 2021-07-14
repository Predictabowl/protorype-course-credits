<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\ExamDistance;
use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;

class ExamDistanceByName1 implements ExamDistance1 {

    private $option;
    private $taken;
    private $distance;
    
    public function __construct(ExamOptionDTO $option, TakenExamDTO $taken) {
        $this->option = $option;
        $this->taken = $taken;
        $this->distance = $this->calculateDistance();
    }

    
    protected function calculateDistance(): int {
        return levenshtein($this->option->getExamName(),
                $this->taken->getExamName(), 1, 2, 1);
    }

    public function getDistance(): int {
        return $this->distance;
    }

}
