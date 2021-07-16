<?php

namespace App\Services\Interfaces;

use App\Domain\Interfaces\ExamDTO;

interface ExamDistance
{
	public function calculateDistance(ExamDTO $exam1, ExamDTO $exam2): int;
}