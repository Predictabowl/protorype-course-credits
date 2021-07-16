<?php


namespace App\Services\Interfaces;

use Illuminate\Support\Collection;;

interface FrontManager
{
	public function setFront(int $id): FrontManager;
	public function getExamBlocks(): Collection;
	public function getTakenExams(): Collection;
        public function getExamOptions(): Collection;
}