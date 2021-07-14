<?php

namespace App\Domain;

class StudyPlan {

    private $approvedExams;

    /**
     * Class Constructor
     * @param    $approvedExam   
     */
    public function __construct() {
        $this->approvedExams = [];
        $this->leftovers = [];
    }

    public function addExam(ApprovedExam $exam) {
        $key = $exam->getExamOption()->getPK();
        if(!isset($this->approvedExams[$key])){
            $this->approvedExams[$key] = $exam;        
        }

        return $this;
    }
    
    public function addExamLink(ExamOptionDTO $option, LinkedTakenExam $taken): LinkedTakenExam {
        $pk = $option->getPK();
        $appExam = (isset($this->approvedExams[$pk])) ?
                $this->approvedExams[$pk] : new ApprovedExam($option);
        $leftover = $appExam->addTakenExam($taken);
        $this->approvedExams[$pk] = $appExam;
        return $leftover;
    }

    public function getExam($pk): ApprovedExam {
        return $this->approvedExams[$pk];
    }

    public function getExams() {
        return $this->approvedExams;
    }

}
