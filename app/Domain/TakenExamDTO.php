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
    private $courseYear;
    private $grade;
    
    public function __construct(
            $id,
            string $examName,
            string $ssd,
            int $maxCfu,
            int $grade,
            ?int $courseYear = null,
            ?int $actualCfu = null) {
        $this->id = $id;
        $this->examName = $examName;
        $this->cfu = $maxCfu;
        $this->ssd = strtoupper($ssd);
        $this->grade = $grade;
        $this->validateAndSetActualCfu($actualCfu);
        $this->courseYear = $courseYear;
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
    
    public function getCourseYear(): ?int {
        return $this->courseYear;
    }

    public function setCourseYear(?int $courseYear): void {
        $this->courseYear = $courseYear;
    }
    
    public function getGrade(): int {
        return $this->grade;
    }

    public function setGrade(int $grade): void {
        $this->grade = $grade;
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
        return serialize($this->__serialize());
    }

    public function unserialize(string $serialized): void {
        $this->__unserialize(unserialize($serialized));
    }
    
    public function __serialize(): array {
        return [
            "id" => $this->id,
            "examName" => $this->examName,
            "cfu" => $this->cfu,
            "ssd" => $this->ssd,
            "actualCfu" => $this->actualCfu,
            "courseYear" => $this->courseYear,
            "grade" => $this->grade
        ];
    }
    
    public function __unserialize(array $data) {
        $this->id = $data["id"];
        $this->examName = $data["examName"];
        $this->cfu = $data["cfu"];
        $this->ssd = $data["ssd"];
        $this->actualCfu = $data["actualCfu"];
        $this->courseYear = $data["courseYear"];
        $this->grade = $data["grade"];
    }

}
