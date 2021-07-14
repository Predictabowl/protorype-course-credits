<?php


namespace App\Services\Implementations;

use App\Domain\DeclaredExam;
use App\Models\Front;
use App\Services\Interfaces\FrontManager;
use Illuminate\Support\Collection;

/**
 * No Implemented Yet
 */
class FrontManagerImpl implements FrontManager
{
	private $studyPlan;
	private $front;
	
	function __construct(Front $front = null)
	{
		if(isset($front)){
			$this->front = $front;
			$this->setUp();
		} else {
			$this->studyPlan = collect([]);
		}
	}

	public function setFront(Front $front)
	{
		$this->front = $front;
		$this->setUp();
	}

	public function getExamBlocks(): Collection
	{
		return $this->studyPlan->examBlocks;
	}

	public function getTakenExams(): Collection
	{
		return $this->front->takenexams;
	}

	public function getStudyPlan(): Collection
	{
		return $this->studyPlan;
	}

	public function getDeclaredExams(): Collection
	{
		// to check if it have a N+1 queries problem via ssd
		return $this->front->takenexams->map(fn ($exam) => 
			new DeclaredExam($exam));
	}

	private function setUp(){
		$this->studyPlan  = $this->front->course->first()->with("examBlocks.examBlockOptions.examApproved")->first();
	}



	public function getExamsBySsd(string $ssd): Collection
	{
		throw new \Exception('Method getExamsBySsd() is not implemented.');
	}

}