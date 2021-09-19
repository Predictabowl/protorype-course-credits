<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

use App\Domain\Interfaces\ExamDTO;
use Illuminate\Support\Collection;
use App\Exceptions\Custom\InvalidStateException;

/**
 * Description of ExamOptionDTO
 *
 * @author piero
 */
class ExamOptionDTO implements ExamDTO, \Serializable{

    private $id;
    private $examName;
    private $block;
    private $ssd;
    private $compatibleOptions;
    private $linkedTakenExams;
    private $recognizedCredits;

    public function __construct($id, string $examName, ExamBlockDTO $block, ?string $ssd) {
        $this->id = $id;
        $this->examName = $examName;
        $this->block = $block;
        $this->ssd = $ssd;
        $this->compatibleOptions = collect([]);
        $block->setOption($this);
        $this->linkedTakenExams = collect([]);
        $this->calculateRecognizedCredits();
    }
    
    public function getExamName(): string {
        return $this->examName;
    }

    public function getBlock(): ExamBlockDTO {
        if(!isset($this->block)){
            throw new InvalidStateException(__METHOD__ . " " 
                    ."Exam Block null value, likely was not set after unserialization.");
        }
        return $this->block;
    }
    
    public function setBlock(ExamBlockDTO $block): void {
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
        return $this->compatibleOptions;
    }

    public function addCompatibleOption(string $ssd){
        $this->compatibleOptions->push($ssd);
    }
    
    public function setCompatibleOptions(Collection $ssds){
        $this->compatibleOptions = $ssds;
    }
    
        /**
     * @return mixed
     */
    public function getTakenExams(): Collection
    {
        return $this->linkedTakenExams;
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
        return serialize([
            "id" => $this->id,
            "examName" => $this->examName,
            "ssd" => $this->ssd,
            "compatibleOptions" => $this->compatibleOptions,
            "linkedTakenExams" => $this->linkedTakenExams,
            "recognizedCredits" => $this->recognizedCredits,
        ]);
    }

    public function unserialize(string $serialized): void {
        $array = unserialize($serialized);
        $this->id = $array["id"];
        $this->examName = $array["examName"];
        $this->block = null;
        $this->ssd = $array["ssd"];
        $this->compatibleOptions = $array["compatibleOptions"];
        $this->linkedTakenExams = $array["linkedTakenExams"];
        $this->recognizedCredits = $array["recognizedCredits"];
    }

    public function getCourseYear(): ?int {
        return $this->getBlock()->getCourseYear();
    }

}
