<?php

namespace App\Services\Interfaces;

use Illuminate\Support\Collection;
use App\Domain\TakenExamDTO;

interface FrontInfoManager {
       
    /**
     * Set the course of the current front.
     * If a course is already set it will be changed.
     * 
     * @param type $courseId
     * @return int 0 if the front doesn't exists
     */
    public function setCourse($courseId): int;
    
    /**
     * Returns null if the course is not set
     */

    public function getActiveFrontId(): ?int;

    public function getExamBlocks(): Collection;

    public function getTakenExams(): Collection;

    public function getExamOptions(): Collection;

    //public function saveTakenExam(TakenExamDTO $exam);

    //public function deleteTakenExam($examId);
    
    /**
     * Wipe all attached TakenExams from the front
     */
    //public function wipeTakenExams();
}
