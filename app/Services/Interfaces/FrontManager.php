<?php


namespace App\Services\Interfaces;

use Illuminate\Support\Collection;
use App\Domain\TakenExamDTO;

interface FrontManager
{
	public function setFront(int $id): FrontManager;
        public function createFront($courseId, $userId): int;
        public function changeCourse($courseId): int;
        public function delete($id): int;
	public function getExamBlocks(): Collection;
	public function getTakenExams(): Collection;
        public function getExamOptions(): Collection;
        public function saveTakenExam(TakenExamDTO $exam);
        public function deleteTakenExam($id);
}