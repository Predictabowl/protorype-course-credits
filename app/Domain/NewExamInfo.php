<?php

namespace App\Domain;

use App\Exceptions\Custom\InvalidInputException;

class NewExamInfo{

    private string $name;
    private ?SsdCode $ssd;
    private bool $freeChoice;

    public function __construct(string $name, ?string $ssd,
            bool $freeChoice = false) {
        $this->name = $name;
        $this->freeChoice = $freeChoice;
        $this->validateCode($ssd);
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSsd(): ?string {
        if (isset($this->ssd)){
            return $this->ssd->getCode();
        }
        return null;
    }

    public function isFreeChoice(): bool {
        return $this->freeChoice;
    }
    
    
    private function validateCode(?string $ssd){
        if ($this->freeChoice){
            $this->ssd = null;
        } else {
            if(isset($ssd)){
                $this->ssd = new SsdCode($ssd);
            } else {
                throw new InvalidInputException("Ssd cannot be null if exam is not free choice.");
            }
        }
    }
}
