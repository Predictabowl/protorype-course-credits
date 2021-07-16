<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

use App\Domain\Interfaces\ExamDTO;

/**
 * Description of TakenExamDTO
 *
 * @author piero
 */
class TakenExamDTO implements ExamDTO{

    private $id;
    private $examName;
    private $cfu;
    private $ssd;
    
    public function __construct($id, string $examName, string $ssd, int $maxCfu) {
        $this->id = $id;
        $this->examName = $examName;
        $this->cfu = $maxCfu;
        $this->ssd = $ssd;
    }
    
    public function getExamName(): string {
        return $this->examName;
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
}
