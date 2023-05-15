<?php

namespace App\Domain;

use App\Domain\ExamBlockStudyPlanDTO;
use App\Domain\ExamStudyPlanDTO;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Serializable;
use function collect;

class StudyPlan implements Serializable{

    private Collection $examBlocks;
    private Collection $leftovers;
    private ?int $maxCfu;

    /**
     * Class Constructor
     * @param    $approvedExam   
     */
    public function __construct(?Collection $examBlocks = null, ?int $maxCfu = null) {
        $this->examBlocks = collect([]);
        if(isset($examBlocks)){
            $examBlocks->each(function(ExamBlockStudyPlanDTO $block){
                $this->addExamBlock($block);
            });
        }
        $this->leftovers = collect([]);
        $this->maxCfu = $maxCfu;
    }
    
    public function addExamBlock(ExamBlockStudyPlanDTO $block): void{
        $this->examBlocks[$block->getId()] = $block;
    }
    
    public function getMaxCfu(): ?int {
        return $this->maxCfu;
    }
    
    public function setMaxCfu(int $maxCfu){
        $this->maxCfu = $maxCfu;
    }

    public function addExamLink(ExamStudyPlanDTO $option, TakenExamDTO $taken): TakenExamDTO {
        $id = $option->getId();
        $appExam = $this->getExam($id);
        if (!isset($appExam)){
            throw new InvalidArgumentException(__METHOD__.": could not find exam option with id :".$id);
        }
        $linkInserted = $appExam->addTakenExam($taken, $this->getLeftoverAllottedCfu());
        $this->setExam($appExam);
        return $linkInserted;
    }
    
    public function getExam($id): ?ExamStudyPlanDTO{
        return $this->getExams()->first(fn(ExamStudyPlanDTO $exam) =>
                $exam->getId() === $id);
    }
    
    public function setExam(ExamStudyPlanDTO $exam){
        $id = $exam->getBlock()->getId();
        $this->examBlocks[$id]->setOption($exam);
    }

    public function getExams(): Collection {
        return $this->examBlocks->map(fn(ExamBlockStudyPlanDTO $block) =>
                $block->getExamOptions())->flatten();
    }
    
    public function getExamBlocks(): Collection {
        return $this->examBlocks;
    }
    
    public function getRecognizedCredits(): int {
        return $this->getExamBlocks()->map(fn(ExamBlockStudyPlanDTO $block) =>
                $block->getRecognizedCredits())->sum();
    }
    
    public function setLeftoverExams(Collection $leftovers){
        $this->leftovers = $leftovers;
    }
    
    public function getLeftoverExams(): Collection{
        return $this->leftovers;
    }
    
    public function getLeftoverAllottedCfu(): ?int{
        if (isset($this->maxCfu)){
            return $this->maxCfu - $this->getRecognizedCredits();
        }
        return null;
    }
    
    public function serialize(): string {
        return serialize([
            "examBlocks" => $this->examBlocks,
            "maxCfu" => $this->maxCfu,
            "leftovers" => $this->leftovers
        ]);
    }

    public function unserialize(string $serialized): void {
        $array = unserialize($serialized);
        $this->maxCfu = $array["maxCfu"];
        $this->examBlocks = $array["examBlocks"];
        $this->leftovers = $array["leftovers"];
    }

    public function __serialize(): array{
        return [
          "examBlocks" => $this->examBlocks,
          "leftovers" => $this->leftovers,
          "maxCfu" => $this->maxCfu
        ];
    }
    
    public function __unserialize(array $data): void {
        $this->examBlocks = $data["examBlocks"];
        $this->leftovers = $data["leftovers"];
        $this->maxCfu = $data["maxCfu"];
    }
}
