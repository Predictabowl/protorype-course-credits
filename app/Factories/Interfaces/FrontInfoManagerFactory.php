<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Interfaces;

use App\Services\Interfaces\FrontInfoManager;
use App\Models\Front;

/**
 *
 * @author piero
 */
interface FrontInfoManagerFactory {
    
    public function getInstance($frontId): FrontInfoManager;
}
