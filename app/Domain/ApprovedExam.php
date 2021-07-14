<?php

namespace App\Domain;

/**
 * 
 */
class ApprovedExam
{
    private $examOption;
    private $linkedTakenExams;
    private $integrationValue;


    /**
     * Class Constructor
     * @param    $name   
     * @param    $declaredExams   
     */
    public function __construct(ExamOptionDTO $examOption)
    {
        $this->examOption = $examOption;
        $this->linkedTakenExams = [];
        $this->calculateIntegrationValue();
    }

    /**
     * @return string
     */
    public function getExamOption(): ExamOptionDTO
    {
        return $this->examOption;
    }


    /**
     * @return mixed
     */
    public function getTakenExams()
    {
        return $this->linkedTakenExams;
    }
    
    public function getTakenExam($id): LinkedTakenExam{
        return $this->linkedTakenExams[$id];
    }

    /**
     * The object will be added only if there's no decifit in the
     * Integration value.
     * 
     * @param DeclaredExam $declaredExams
     *
     * @return Integration Value decifit.
     */
    public function addTakenExam(LinkedTakenExam $exam): LinkedTakenExam
    {
        if (!$this->isTakenExamAddable($exam)){
            return $exam;
        }

        $value = $this->getIntegrationValue();
        if ($value > $exam->getActualCfu()){
            $value = $exam->getActualCfu();
        }

        $this->linkedTakenExams[$exam->getTakenExam()->getId()] = $exam->split($value);
        $this->calculateIntegrationValue();
        return $exam;
    }
    
    public function isTakenExamAddable(LinkedTakenExam $exam): bool
    {
        if ($this->getIntegrationValue() < 1){
            return false;
        }
        return true;
    }

    
    public function getIntegrationValue(): int
    {
        return $this->integrationValue;
    }
    
    private function calculateIntegrationValue(){
        $this->integrationValue = $this->examOption->getCfu() -
            collect($this->linkedTakenExams)
            ->map(fn ($item) => $item->getActualCfu())
            ->sum();
    }
}