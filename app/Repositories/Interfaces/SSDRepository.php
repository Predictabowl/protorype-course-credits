<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Repositories\Interfaces;

use App\Models\Ssd;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface SSDRepository {
    public function getAll(): Collection;
    public function getSsdFromCode(string $ssd): ?Ssd;
    public function getSsdFromCodeWithExamBlocks(string $ssd): ?Ssd;
}
