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
    }

    public function addExamLink(ExamOptionDTO $option, LinkedTakenExam $taken): LinkedTakenExam {
        $id = $option->getId();
        $appExam = (isset($this->approvedExams[$id]))
                ? $this->approvedExams[$id]
                : new ApprovedExam($option);
        $linkInserted = $appExam->addTakenExam($taken);
        $this->approvedExams[$id] = $appExam;
        return $linkInserted;
    }

    public function getExam($id): ApprovedExam {
        return $this->approvedExams[$id];
    }

    public function getExams() {
        return $this->approvedExams;
    }
    
    public function getIntegrationValue(): int {
        return collect($this->approvedExams)->map(fn(ApprovedExam $exam) =>
                $exam->getIntegrationValue())->sum();
    }

}
