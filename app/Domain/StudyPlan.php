<?php

namespace App\Domain;

use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Serializable;
use function collect;

class StudyPlan implements Serializable{

    private $examBlocks;
    private $leftovers;
    private $maxCfu;

    /**
     * Class Constructor
     * @param    $approvedExam   
     */
    public function __construct(Collection $examBlocks, ?int $maxCfu = null) {
        $this->examBlocks = $examBlocks->mapWithKeys(
                fn(ExamBlockDTO $block) => 
                    [$block->getId() => $block]);
        $this->leftovers = collect([]);
        $this->maxCfu = $maxCfu;
    }
    
    public function getMaxCfu(): ?int {
        return $this->maxCfu;
    }
    
    public function setMaxCfu(int $maxCfu){
        $this->maxCfu = $maxCfu;
    }

    public function addExamLink(ExamOptionDTO $option, TakenExamDTO $taken): TakenExamDTO {
        $id = $option->getId();
        $appExam = $this->getExam($id);
        if (!isset($appExam)){
            throw new InvalidArgumentException(__METHOD__.": could not find exam option with id :".$id);
        }
        $linkInserted = $appExam->addTakenExam($taken, $this->getLeftoverAllottedCfu());
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
        return $this->getExamBlocks()->map(fn(ExamBlockDTO $block) =>
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
