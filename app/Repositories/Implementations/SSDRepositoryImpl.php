<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Repositories\Implementations;

use App\Models\Ssd;
use App\Repositories\Interfaces\SSDRepository;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
class SSDRepositoryImpl implements SSDRepository
{
    
    public function getSsdFromCode(string $ssd): ?Ssd {
        return Ssd::where("code", $ssd)->first();
    }

    public function getSsdFromCodeWithExamBlocks(string $ssd): ?Ssd {
        return Ssd::where("code", $ssd)->with("examBlocks")->first();
    }

    public function getAll(): Collection {
        return Ssd::all();
    }

}
