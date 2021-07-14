<?php

namespace App\Services\Interfaces;

use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;

interface ExamDistance
{
	public function calculateDistance(ExamOptionDTO $option, TakenExamDTO $taken): int;
}