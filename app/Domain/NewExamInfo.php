<?php

namespace App\Domain;

use App\Support\Seeders\GenerateSSD;
use InvalidArgumentException;

class NewExamInfo{

    private string $name;
    private string $ssd;

    public function __construct(string $name, string $ssd) {
        $this->name = $name;
        $this->ssd = strtoupper($ssd);
        $this->validateSelf();
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSsd(): string {
        return $this->ssd;
    }
    
    private function validateSelf(){
        if(!GenerateSSD::isPossibleSSD($this->ssd)){
            throw new InvalidArgumentException("Invalid SSD format: ".$this->ssd);
        }
    }
}
