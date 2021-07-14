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

    private $examName;
    private $block;
    private $cfu;
    private $ssd;
    private $compatibleOptions;

    public function __construct(string $examName, ExamBlockDTO $block, int $cfu, string $ssd) {
        $this->examName = $examName;
        $this->block = $block;
        $this->cfu = $cfu;
        $this->ssd = $ssd;
        $this->compatibleOptions = [];
    }
    
    public function getExamName() {
        return $this->examName;
    }

    public function getBlock() {
        return $this->block;
    }

    public function getCfu() {
        return $this->cfu;
    }

    public function getSsd() {
        return $this->ssd;
    }
    
    //override as fit to get a primary key
    public function getPK(){
        return $this->examName;
    }

    public function getCompatibleOptions() {
        return $this->compatibleOptions;
    }

    public function addCompatibleOption(ExamOptionDTO $option){
        $this->compatibleOptions[] = $option;
    }
}
