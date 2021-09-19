<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Interfaces;

use App\Models\Front;
use App\Services\Interfaces\StudyPlanManager;

/**
 *
 * @author piero
 */
interface StudyPlanManagerFactory {
    
    public function get(Front $front): StudyPlanManager;
}
