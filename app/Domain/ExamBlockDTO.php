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
