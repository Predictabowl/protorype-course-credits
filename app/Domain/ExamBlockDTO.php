<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

/**
 * Description of ExamBlockDTO
 *
 * @author piero
 */
class ExamBlockDTO{
    
    private $id;
    private $approvedExams;
    private $numExams;
    
    public function __construct($id, int $numExams) {
        $this->id = $id;
        $this->approvedExams = collect([]);
        $this->numExams = $numExams;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setOption(ExamOptionDTO $option) {
        $this->approvedExams[$option->getId()] = $option;
        return $this;
    }
    
    public function removeOption(ExamOptionDTO $option) {
        unset($this->approvedExams[$option->getId()]);
        return $this;
    }

    public function getNumExams() {
        return $this->numExams;
    }

    public function getExamOptions() {
        return $this->approvedExams;
    }
    
    public function getExamOption($id): ExamOptionDTO {
        return $this->approvedExams[$id];
    }
    
    public function getIntegrationValue(): int{
        
        return $this->getCfu() - $this->getRecognizedCredits();
    }
    
    public function getRecognizedCredits(): int{
        
        return $this->approvedExams->map(fn(ExamOptionDTO $exam) => 
                    $exam->getTakenExams()->map(fn(TakenExamDTO $taken)=> 
                        $taken->getActualCfu()
                    )
                )->flatten()->sum();
    }
    
    // Is supposed that every exam in the block have the same cfu value
    public function getCfu(): int {
        $exam = $this->approvedExams->first();
        if (!isset($exam)){
            return 0;
        }
        return $exam->getCfu() * $this->numExams;
    }
    
    /**
     * Return the number of exams that can actually be used,
     * which is the maximum number of options minus the ones already taken,
     * even if the taken options are not completely integrated
     */
    public function getNumSlotsAvailable(): int{
        return $this->getNumExams() - 
                $this->approvedExams->map(fn(ExamOptionDTO $exam) =>
                        $exam->getTakenExams()->isEmpty() ? 0 : 1)
                ->sum();
    }
    
//    public function setApprovedExam(ExamOptionDTO $exam) {
//        $this->approvedExams[$exam->getId()] = $exam;
//        return $this;
//    }
    
//    public function getApprovedExams() {
//        return $this->approvedExams;
//    }
//    
//    public function getApprovedExam($id): ExamOptionDTO{
//        return $this->approvedExams->get($id);
//    }
}
