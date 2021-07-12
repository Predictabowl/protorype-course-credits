<?php

namespace App\Domain;

use App\Models\TakenExam;
use InvalidArgumentException;

/**
 * 
 */
class ExamAssignedValue
{
	private $takenExam;
	private $cfuValue;


	/**
	 * Class Constructor
	 * @param    $takenExam   
	 * @param    $cfuValue   
	 */
	public function __construct(TakenExam $takenExam, int $cfuValue = 0)
	{
		$this->takenExam = $takenExam;
		if ($cfuValue === 0){
			$cfuValue = $this->takenExam->getAttribute("cfu");	
		}
		$this->validateAndSetCfu($cfuValue);
	}

	private function validateAndSetCfu(int $value)
	{
		if ($value <= 0 || $value > $this->takenExam->getAttribute("cfu")){
			throw new InvalidArgumentException("The value must be within 0 and Exam cfu value");
		}

		$this->cfuValue = $value;

	}	

    /**
     * @return mixed
     */
    public function getTakenExam(): TakenExam
    {
        return $this->takenExam;
    }

    public function getCfuValue(): int
    {
        return $this->cfuValue;
    }

    public function getExcessCfu()
    {
    	return $this->takenExam->getAttribute("cfu") - $this->cfuValue;
    }

    public function setCfuValue(int $value)
    {
		$this->validateAndSetCfu($value);
    }

}