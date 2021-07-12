<?php


namespace App\Services\Interfaces;

use App\Models\Exam;
use App\Models\ExamBlockOption;
use App\Models\TakenExam;
use Illuminate\Support\Collection;

interface  LinkedExams
{
	public function addTakenExam(TakenExam $exam, int $cfuValue = 0): int;
	public function getTakenExams(): Collection;
    public function getGrantedExam(): ExamBlockOption;
    public function getIntegrationValue();
	public function removeTakenExam(TakenExam $exam);
}