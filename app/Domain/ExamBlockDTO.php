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
class ExamBlockDTO {
    
    private $examOptions;
    private $numExams;
    
    public function __construct(int $numExams) {
        $this->examOptions = [];
        $this->numExams = $numExams;
    }
    
    public function addOption(ExamOptionDTO $option) {
        $this->examOptions[$option->getExamName()] = $option;
        return $this;
    }
    
    public function removeOption(ExamOptionDTO $option) {
        unset($this->examOptions[$option->getExamName()]);
        return $this;
    }

    public function getNumExams() {
        return $this->numExams;
    }

    public function getExamOptions() {
        return $this->examOptions;
    }
}
