<?php



namespace App\Services\Implementations;

use App\Domain\ExamAssignedValue;
use App\Models\ExamBlockOption;
use App\Models\TakenExam;
use App\Services\Interfaces\LinkedExams;
use Illuminate\Support\Collection;

class LinkedExamsUnit implements LinkedExams
{
	private $takenExams;
	private $grantedExam;


	/**
	 * Class Constructor
	 * @param    $grantedExam   
	 */
	public function __construct(ExamBlockOption $grantedExam)
	{
		$this->grantedExam = $grantedExam;
	}

	public function getTakenExams(): Collection
	{
		return collect($this->takenExams);
	}

	public function addTakenExam(TakenExam $exam, int $cfuValue = 0): int
	{
		$integrationValue = $this->getIntegrationValue();
		if ($integrationValue < 1){
			return 0;
		} elseif ($integrationValue < $cfuValue){
			$cfuValue = $integrationValue;
		}
		$takenExam = new ExamAssignedValue($exam,$cfuValue);
		$this->takenExams[$exam->id] = $takenExam;
		return $takenExam->getCfuValue();
	}

	public function removeTakenExam(TakenExam $exam){
		unset($this->takenExams[$exam->id]);
	}

    public function getGrantedExam(): ExamBlockOption
    {
        return $this->grantedExam;
    }

    public function getIntegrationValue()
    {
    	$maxCfu = $this->grantedExam->getAttribute("cfu");
    	return $maxCfu - $this->getTakenExams()
    		->map(fn ($item) => $item->getCfuValue())
    		->sum();
    }

}