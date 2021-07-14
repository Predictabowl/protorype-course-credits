<?php

namespace App\Factories\Implementations;


use App\Factories\Interfaces\ExamDistanceFactory;
use App\Services\Implementations\ExamDistanceByName;
use App\Services\Interfaces\ExamDistance;
use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;

class ExamDistanceByNameFactory implements ExamDistanceFactory
{


	public function getInstance(ExamOptionDTO $courseExam, TakenExamDTO $takenExam): ExamDistance
	{
		return new ExamDistanceByName($courseExam, $takenExam);
	}
}