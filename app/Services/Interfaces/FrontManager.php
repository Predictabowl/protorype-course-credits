<?php


namespace App\Services\Interfaces;

use Illuminate\Support\Collection;
use App\Domain\TakenExamDTO;

interface FrontManager
{
	public function setFront(int $id): FrontManager;
	public function getExamBlocks(): Collection;
	public function getTakenExams(): Collection;
        public function getExamOptions(): Collection;
        public function saveTakenExam(TakenExamDTO $exam);
}