<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

/**
 * Description of TakenExamDTO
 *
 * @author piero
 */
class TakenExamDTO {

    private $examName;
    private $cfu;
    private $ssd;
    
    public function __construct(string $examName, string $ssd, int $maxCfu) {
        $this->examName = $examName;
        $this->cfu = $maxCfu;
        $this->ssd = $ssd;
    }
    
    public function getExamName() {
        return $this->examName;
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
}
