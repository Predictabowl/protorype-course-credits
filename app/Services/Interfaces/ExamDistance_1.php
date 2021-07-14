<?php

namespace App\Services\Interfaces;

use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;

interface ExamDistance1
{
	public function getDistance(): int;
}