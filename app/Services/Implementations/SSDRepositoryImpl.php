<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Services\Implementations;

use App\Models\Ssd;
use App\Services\Interfaces\SSDRepository;

/**
 *
 * @author piero
 */
class SSDRepositoryImpl implements SSDRepository
{
    
    public function getSsdFromCode(string $ssd): ?Ssd {
        return Ssd::where("code", $ssd)->first();
    }

}
