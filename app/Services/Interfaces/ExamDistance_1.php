<?php

namespace App\Services\Interfaces;

use App\Domain\ExamBlockOption;
use App\Domain\TakenExamDTO;

interface ExamDistance1
{
	public function getDistance(): int;
}