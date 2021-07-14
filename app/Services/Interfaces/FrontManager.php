<?php


namespace App\Services\Interfaces;

use App\Models\Front;
use Illuminate\Support\Collection;;

interface FrontManager
{
	public function setFront(Front $front);
	public function getExamBlocks(): Collection;
	public function getTakenExams(): Collection;
        public function getExamOptions(): Collection;
}