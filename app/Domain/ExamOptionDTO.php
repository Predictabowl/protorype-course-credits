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

    public function __construct($id, string $examName, ExamBlockDTO $block, int $cfu, string $ssd) {
        $this->id = $id;
        $this->examName = $examName;
        $this->block = $block;
        $this->cfu = $cfu;
        $this->ssd = $ssd;
        $this->compatibleOptions = collect([]);
        $block->addOption($this);
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
}
