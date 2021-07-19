<?php

namespace App\Services\Interfaces;

use Illuminate\Support\Collection;
use App\Domain\TakenExamDTO;

interface FrontManager {
       
    public function getTakenExams(): Collection;

    public function saveTakenExam(TakenExamDTO $exam);

    public function deleteTakenExam($examId);
    
    /**
     * Wipe all attached TakenExams from the front
     */
    //public function wipeTakenExams();
}
