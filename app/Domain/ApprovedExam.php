<?php

namespace App\Domain;

/**
 * 
 */
class ApprovedExam
{
    private $examOption;
    private $linkedTakenExams;


    /**
     * Class Constructor
     * @param    $name   
     * @param    $declaredExams   
     */
    public function __construct(ExamOptionDTO $examOption)
    {
        $this->examOption = $examOption;
        $this->linkedTakenExams = [];
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
    
    public function getTakenExam($pk): LinkedTakenExam{
        return $this->linkedTakenExams[$pk];
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
        $value = $this->getIntegrationValue();
        if ($value < 1){
            return $exam;
        }

        if ($value > $exam->getActualCfu()){
            $value = $exam->getActualCfu();
        }

        $this->linkedTakenExams[$exam->getTakenExam()->getPK()] = $exam->split($value);
        return $exam;
    }

    public function getIntegrationValue(): int
    {
        return $this->examOption->getCfu() -
            collect($this->linkedTakenExams)
            ->map(fn ($item) => $item->getActualCfu())
            ->sum();
    }
}