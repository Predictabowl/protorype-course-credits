<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Interfaces;

use App\Models\Course;
use App\Services\Interfaces\StudyPlanBuilder;

/**
 *
 * @author piero
 */
interface StudyPlanBuilderFactory {
    
    public function get(int $frontId, int $courseId): StudyPlanBuilder;
}
