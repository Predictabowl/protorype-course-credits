<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

/**
 * Description of ExamBlockLinker
 *
 * @author piero
 */
class ExamBlockLinker {
    
    private $block;
    private $linkedExams;
    
    public function __construct(ExamBlockDTO $block) {
        $this->block = $block;
        $this->linkedExams = [];
    }
    
    public function getBlock() {
        return $this->block;
    }

    public function getLinkedExams() {
        return $this->linkedExams;
    }

    public function linkExam($pk): bool {
        if (!isset($this->linkedExams[$pk])){
            if($this->block->getNumExams() <= count($this->linkedExams)){
                return false;
            }
            $this->linkedExams[$pk] = $this->block->getExamOption($pk);
        }
        return true;
    }
}
