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
class TakenExamDTO implements ExamDTO, \Serializable{

    private $id;
    private $examName;
    private $cfu;
    private $ssd;
    private $actualCfu;
    
    public function __construct($id, string $examName, string $ssd, 
            int $maxCfu, ?int $actualCfu = null) {
        $this->id = $id;
        $this->examName = $examName;
        $this->cfu = $maxCfu;
        $this->ssd = strtoupper($ssd);
        $this->validateAndSetActualCfu($actualCfu);
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
    
    public function getActualCfu(): int {
        return $this->actualCfu;
    }
    
    public function setActualCfu(int $value)
    {
        $this->validateAndSetActualCfu($value);
        return $this;
    }
    
    public function split(int $value): TakenExamDTO
    {
        $clone = clone $this;
        $this->setActualCfu($this->actualCfu-$value);
        $clone->setActualCfu($value);
        return $clone;
    }

    private function validateAndSetActualCfu($value)
    {
        if (!isset($value)){
            $value = $this->cfu;
        }elseif (gettype($value) != "integer"){
            throw new \InvalidArgumentException("The actual cfu value must be an integer");
        }elseif ($value < 0){
            throw new \InvalidArgumentException("The actual cfu value must be positive");
        }elseif($value > $this->cfu){
            throw new \InvalidArgumentException("The actual cfu cannot be higher than the max cfu value");
        }
        $this->actualCfu = $value;
    }

    public function serialize(): string {
        return serialize([
            "id" => $this->id,
            "examName" => $this->examName,
            "cfu" => $this->cfu,
            "ssd" => $this->ssd,
            "actualCfu" => $this->actualCfu
        ]);
    }

    public function unserialize(string $serialized): void {
        $array = unserialize($serialized);
        $this->id = $array["id"];
        $this->examName = $array["examName"];
        $this->cfu = $array["cfu"];
        $this->ssd = $array["ssd"];
        $this->actualCfu = $array["actualCfu"];
    }

}
