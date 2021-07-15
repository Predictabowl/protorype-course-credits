<?php

namespace App\Factories\Interfaces;

use App\Services\Interfaces\ExamDistance;
use App\Domain\ExamBlockOption;
use App\Domain\TakenExamDTO;

interface ExamDistanceFactory
{
	public function getInstance(ExamBlockOption $courseExam, TakenExamDTO $takenExam): ExamDistance;
}