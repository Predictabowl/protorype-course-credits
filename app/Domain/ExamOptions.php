<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Domain;

use Illuminate\Support\Collection;

/**
 * Description of ExamOptions
 *
 * @author piero
 */
class ExamOptions{
    
    private Collection $options;
    
    public function __construct() {
        $this->options = collect([]);
    }

    public function add(): void{
        
    }
    
    public function get(): Collection {
        
    }
}
