<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

use InvalidArgumentException;

/**
 * Description of LinkedTakenExam
 *
 * @author piero
 */
class LinkedTakenExam2 {
    
    private $takenExam;
    private $actualCfu;
    
    public function __construct(TakenExamDTO $takenExam, $actualCfu = null) {
        $this->takenExam = $takenExam;
        $this->validateAndSetActualCfu($actualCfu);
    }
    
    public function getTakenExam(): TakenExamDTO {
        return $this->takenExam;
    }

    public function getActualCfu(): int {
        return $this->actualCfu;
    }
    
    public function setActualCfu(int $value)
    {
        $this->validateAndSetActualCfu($value);
        return $this;
    }

    public function split(int $value): LinkedTakenExam
    {
        $this->setActualCfu($this->actualCfu-$value);
        return new LinkedTakenExam($this->takenExam, $value);
    }

    private function validateAndSetActualCfu($value)
    {
        if (!isset($value)){
            $value = $this->takenExam->getCfu();
        }elseif (gettype($value) != "integer"){
            throw new InvalidArgumentException("The actual cfu value must be an integer");
        }elseif ($value < 0){
            throw new InvalidArgumentException("The actual cfu value must be positive");
        }elseif($value > $this->takenExam->getCfu ()){
            throw new InvalidArgumentException("The actual cfu cannot be higher than the max cfu value");
        }
        $this->actualCfu = $value;
    }   

}
