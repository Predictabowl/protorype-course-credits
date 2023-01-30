<?php

namespace App\Services\Interfaces;

use App\Models\Front;
use Illuminate\Support\Collection;

interface FrontManager {
       
    public function getTakenExams(): Collection;

    public function saveTakenExam($attributes);

    public function deleteTakenExam($examId);
    
    public function deleteAllTakenExams();
    
    public function setCourse($courseId): bool;
    
    public function getFront(): Front;
    
}
