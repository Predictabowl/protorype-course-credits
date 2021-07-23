<?php

namespace App\Domain;

use Illuminate\Support\Collection;

use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;

class StudyPlan {

//    private $approvedExams;
    private $examBlocks;

    /**
     * Class Constructor
     * @param    $approvedExam   
     */
    public function __construct(Collection $examBlocks) {
//        $this->approvedExams = collect([]);
        $this->examBlocks = $examBlocks->mapWithKeys(
                fn(ExamBlockDTO $block) => 
                    [$block->getId() => $block]);
//        $this->examBlocks->each(function (ExamBlockDTO $block){ 
//                collect($block->getExamOptions())
//                        ->each(function (ExamOptionDTO $option){
//                            $this->setExam(new ApprovedExam($option));
//                });
//        });
    }

//    public function addExamLink(ExamOptionDTO $option, LinkedTakenExam $taken): LinkedTakenExam {
//        $id = $option->getId();
//        $appExam = (isset($this->approvedExams[$id]))
//                ? $this->approvedExams[$id]
//                : new ApprovedExam($option);
//        $linkInserted = $appExam->addTakenExam($taken);
//        $this->approvedExams[$id] = $appExam;
//        return $linkInserted;
//    }

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
//        $id = $exam->getExamOption()->getId();
//        $this->approvedExams[$id] = $exam;
    }

    public function getExams() {
        return $this->examBlocks->map(fn(ExamBlockDTO $block) =>
                $block->getExamOptions())->flatten();
//        return $this->approvedExams;
    }
    
    public function getExamBlocks() {
        return $this->examBlocks;
    }
    
    public function getIntegrationValue(): int {
        return $this->examBlocks->map(fn(ExamBlockDTO $block) =>
                $block->getExamOptions()->map(fn(ExamOptionDTO $exam) =>
                    $exam->getIntegrationValue()))->flatten()->sum();
//        return collect($this->approvedExams)->map(fn(ApprovedExam $exam) =>
//                $exam->getIntegrationValue())->sum();
    }
}
