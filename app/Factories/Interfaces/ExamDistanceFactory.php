<?php

namespace App\Factories\Interfaces;

use App\Services\Interfaces\ExamDistance;
use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;

interface ExamDistanceFactory
{
	public function getInstance(ExamOptionDTO $courseExam, TakenExamDTO $takenExam): ExamDistance;
}