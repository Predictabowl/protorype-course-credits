<?php

namespace App\Domain;

use Illuminate\Support\Collection;

use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;

class StudyPlan {

    private $examBlocks;

    /**
     * Class Constructor
     * @param    $approvedExam   
     */
    public function __construct(Collection $examBlocks) {
        $this->examBlocks = $examBlocks->mapWithKeys(
                fn(ExamBlockDTO $block) => 
                    [$block->getId() => $block]);
    }

    public function addExamLink(ExamOptionDTO $option, TakenExamDTO $taken): TakenExamDTO {
        $id = $option->getId();
        $appExam = $this->getExam($id);
        if (!isset($appExam)){
            throw new \InvalidArgumentException(__METHOD__.": could not find exam option with id :".$id);
        }
        $linkInserted = $appExam->addTakenExam($taken);
        $this->setExam($appExam);
        return $linkInserted;
    }
    
    public function getExam($id): ?ExamOptionDTO{
        
        return $this->getExams()->first(fn(ExamOptionDTO $exam) =>
                $exam->getId() === $id);
    }
    
    public function setExam(ExamOptionDTO $exam){
        $id = $exam->getBlock()->getId();
        $this->examBlocks[$id]->setOption($exam);
    }

    public function getExams() {
        return $this->examBlocks->map(fn(ExamBlockDTO $block) =>
                $block->getExamOptions())->flatten();
    }
    
    public function getExamBlocks() {
        return $this->examBlocks;
    }
    
    public function getRecognizedCredits(): int {
//        return $this->examBlocks->map(fn(ExamBlockDTO $block) =>
//                $block->getExamOptions()->map(fn(ExamOptionDTO $exam) =>
//                    $exam->getIntegrationValue()))->flatten()->sum();
        return $this->getExamBlocks()->map(fn(ExamBlockDTO $block) =>
                $block->getRecognizedCredits())->sum();
    }
}
