<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

use App\Domain\Interfaces\ExamDTO;
use App\Exceptions\Custom\InvalidStateException;
use Illuminate\Support\Collection;
use Serializable;
use function collect;

/**
 * Description of ExamOptionDTO
 *
 * @author piero
 */
class ExamStudyPlanDTO implements ExamDTO, Serializable{

    private int $id;
    private string $examName;
    private ExamBlockStudyPlanDTO $block;
    private ?string $ssd;
    private Collection $linkedTakenExams;
    private $recognizedCredits;
    private bool $freeChoice;

    public function __construct(int $id, string $examName, ExamBlockStudyPlanDTO $block,
            ?string $ssd, bool $freeChoice = false) {
        $this->id = $id;
        $this->examName = $examName;
        $this->block = $block;
        $block->setOption($this);
        $this->linkedTakenExams = collect([]);
        $this->calculateRecognizedCredits();
        $this->freeChoice = $freeChoice;
        $this->ssd = $ssd;
            
    }

    public function getExamName(): string {
        return $this->examName;
    }

    public function getBlock(): ExamBlockStudyPlanDTO {
        if(!isset($this->block)){
            throw new InvalidStateException(__METHOD__ . " "
                    ."Exam Block null value, likely was not set after unserialization.");
        }
        return $this->block;
    }

    public function setBlock(ExamBlockStudyPlanDTO $block): void {
        $this->block = $block;
    }

    public function getCfu(): int {
        return $this->getBlock()->getCfu();
    }

    public function getSsd(): ?string {
        return $this->ssd;
    }

    public function getId(){
        return $this->id;
    }

    public function getCompatibleOptions(): Collection {
        return $this->block->getCompatibleOptions();
    }

        /**
     * @return mixed
     */
    public function getTakenExams(): Collection
    {
        return $this->linkedTakenExams;
    }

    public function isFreeChoice(): bool {
        return $this->freeChoice;
    }

    public function getTakenExam($id): TakenExamDTO{
        return $this->linkedTakenExams[$id];
    }

     /**
     * The object will be added only if there's no decifit in the
     * Integration value.
     *
     * @param DeclaredExam $declaredExams
     *
     * @return Integration Value decifit.
     */
    public function addTakenExam(TakenExamDTO $exam, ?int $maxCfu = null): TakenExamDTO
    {
        if (!$this->isTakenExamAddable($exam)){
            return $exam;
        }

        $value = $this->getIntegrationValue();
        if (isset($maxCfu) && $value > $maxCfu){
            $value = $maxCfu;
        }
        if ($value > $exam->getActualCfu()){
            $value = $exam->getActualCfu();
        }

        $this->linkedTakenExams[$exam->getId()] = $exam->split($value);
        $this->calculateRecognizedCredits();
        return $exam;
    }

    public function isTakenExamAddable(TakenExamDTO $exam): bool
    {
        if (($this->getIntegrationValue() < 1) || ($exam->getActualCfu() < 1)){
            return false;
        }

        if ($this->linkedTakenExams->isEmpty() &&
                $this->getBlock()->getNumSlotsAvailable() < 1){
            return false;
        }
        return true;
    }

    public function getIntegrationValue(): int
    {
        return $this->getCfu() -
            $this->getRecognizedCredits();
    }

    public function getRecognizedCredits(): int{
        return $this->recognizedCredits;
    }

    private function calculateRecognizedCredits(){
        $this->recognizedCredits = collect($this->linkedTakenExams)
                ->map(fn ($item) => $item->getActualCfu())
                ->sum();
    }

    public function serialize(): string {
        return serialize($this->__serialize());
    }

    public function unserialize(string $serialized): void {
        $this->__unserialize(unserialize($serialized));
    }

    public function __serialize(): array {
        return [
            "id" => $this->id,
            "examName" => $this->examName,
            "ssd" => $this->ssd,
            "linkedTakenExams" => $this->linkedTakenExams,
            "recognizedCredits" => $this->recognizedCredits,
            "block" => $this->block,
            "freeChoice" => $this->freeChoice
        ];
    }

    public function __unserialize(array $data) {
        $this->id = $data["id"];
        $this->examName = $data["examName"];
        $this->block = $data["block"];
        $this->ssd = $data["ssd"];
        $this->linkedTakenExams = $data["linkedTakenExams"];
        $this->recognizedCredits = $data["recognizedCredits"];
        $this->freeChoice = $data["freeChoice"];
    }

    public function getCourseYear(): ?int {
        return $this->getBlock()->getCourseYear();
    }

}
