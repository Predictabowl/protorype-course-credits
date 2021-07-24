<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

use App\Domain\Interfaces\ExamDTO;
use Illuminate\Support\Collection;

/**
 * Description of ExamOptionDTO
 *
 * @author piero
 */
class ExamOptionDTO implements ExamDTO{

    private $id;
    private $examName;
    private $block;
    private $cfu;
    private $ssd;
    private $compatibleOptions;
    private $linkedTakenExams;
    private $recognizedCredits;

    public function __construct($id, string $examName, ExamBlockDTO $block, int $cfu, string $ssd) {
        $this->id = $id;
        $this->examName = $examName;
        $this->block = $block;
        $this->cfu = $cfu;
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
        return $this->block;
    }

    public function getCfu(): int {
        return $this->cfu;
    }

    public function getSsd(): string {
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
    public function addTakenExam(TakenExamDTO $exam): TakenExamDTO
    {
        if (!$this->isTakenExamAddable($exam)){
            return $exam;
        }

        $value = $this->getIntegrationValue();
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
                $this->block->getNumSlotsAvailable() < 1){
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
}
