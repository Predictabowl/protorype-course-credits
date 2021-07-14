<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

/**
 * Description of ExamOptionDTO
 *
 * @author piero
 */
class ExamOptionDTO {

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
        $this->compatibleOptions = [];
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

    public function getCompatibleOptions() {
        return $this->compatibleOptions;
    }

    public function addCompatibleOption(ExamOptionDTO $option){
        $this->compatibleOptions[] = $option;
    }
}
