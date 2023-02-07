<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Domain;

use App\Exceptions\Custom\InvalidInputException;
use App\Support\Seeders\GenerateSSD;
use function __;

/**
 * Description of SsdCode
 *
 * @author piero
 */
class SsdCode {
    
    private string $code;
    
    public function __construct(string $code) {
        $this->code = strtoupper($code);
        $this->validateSelf();
    }
    
    public function getCode(): string {
        return $this->code;
    }

    private function validateSelf(){
        if(!GenerateSSD::isPossibleSSD($this->code)){
            throw new InvalidInputException(__("Invalid SSD format").": ".$this->code);
        }
    }

}
