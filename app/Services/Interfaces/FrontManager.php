<?php

namespace App\Services\Interfaces;

use App\Models\Course;
use App\Models\Front;
use Illuminate\Support\Collection;
use App\Domain\TakenExamDTO;

interface FrontManager {
       
    public function getTakenExams(): Collection;

    public function saveTakenExam(TakenExamDTO $exam);

    public function deleteTakenExam($examId);
    
    public function setCourse($courseId): bool;
    
    public function getFront(): Front;
    
    /**
     * Wipe all attached TakenExams from the front
     */
    //public function wipeTakenExams();
}
