<?php

namespace App\Services\Interfaces;

use App\Models\Front;
use Illuminate\Support\Collection;

interface FrontManager {
       
    public function getAllSSds(): Collection;
    
    public function getTakenExams(int $frontId): Collection;

    public function saveTakenExam($attributes, int $frontId);

    public function deleteTakenExam(int $examId);
    
    public function deleteAllTakenExams(int $frontId);
    
    public function setCourse(int $frontId, int $courseId): bool;
    
    public function getFront(int $frontId): ?Front;

    public function getOrCreateFront(int $userId, ?int $courseId = null): Front;
}
