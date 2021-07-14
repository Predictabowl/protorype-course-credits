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
    
    private $id;
    private $examOptions;
    private $numExams;
    
    public function __construct($id, int $numExams) {
        $this->id = $id;
        $this->examOptions = [];
        $this->numExams = $numExams;
    }
    
    public function getId() {
        return $this->id;
    }

    public function addOption(ExamOptionDTO $option) {
        $this->examOptions[$option->getId()] = $option;
        return $this;
    }
    
    public function removeOption(ExamOptionDTO $option) {
        unset($this->examOptions[$option->getId()]);
        return $this;
    }

    public function getNumExams() {
        return $this->numExams;
    }

    public function getExamOptions() {
        return $this->examOptions;
    }
    
    public function getExamOption($id) {
        return $this->examOptions[$id];
    }
}
