<?php

namespace App\Domain;

use App\Support\Seeders\GenerateSSD;
use InvalidArgumentException;

class NewExamInfo{

    private string $name;
    private ?string $ssd;
    private bool $freeChoice;

    public function __construct(string $name, ?string $ssd,
            bool $freeChoice = false) {
        $this->name = $name;
        $this->freeChoice = $freeChoice;
        if($freeChoice){
            $this->ssd = null;
        } else {
            $this->ssd = strtoupper($ssd);
            $this->validateSelf();
        }
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSsd(): ?string {
        return $this->ssd;
    }

    public function isFreeChoice(): bool {
        return $this->freeChoice;
    }
    
    private function validateSelf(){
        if(!GenerateSSD::isPossibleSSD($this->ssd)){
            throw new InvalidArgumentException(__("Invalid SSD format").": ".$this->ssd);
        }
    }
}
