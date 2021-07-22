<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

use App\Domain\ApprovedExam;

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

    public function addOption(ExamOptionDTO $option) {
        $this->approvedExams[$option->getId()] = new ApprovedExam($option);
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
        return $this->approvedExams->map(fn(ApprovedExam $option) =>
                $option->getExamOption());
    }
    
    public function getExamOption($id) {
        return $this->approvedExams[$id]->getExamOption();
    }
    
    public function setApprovedExam(ApprovedExam $exam) {
        $this->approvedExams[$exam->getExamOption()->getId()] = $exam;
        return $this;
    }
    
    public function getApprovedExams() {
        return $this->approvedExams;
    }
    
    public function getApprovedExam($id){
        return $this->approvedExams->get($id);
    }
}
