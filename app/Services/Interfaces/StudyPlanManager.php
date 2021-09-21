<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Interfaces;

use App\Domain\StudyPlan;

/**
 *
 * @author piero
 */
interface StudyPlanManager {
    
    public function getStudyPlan(): ?StudyPlan;
    
    public function getAcademicYear(): int;
    
    public function getCourseYear(): ?int;
}
