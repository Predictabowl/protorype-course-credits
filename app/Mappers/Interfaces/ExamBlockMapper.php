<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Mappers\Interfaces;

use App\Domain\ExamBlockStudyPlanDTO;
use App\Models\ExamBlock;

/**
 *
 * @author piero
 */
interface ExamBlockMapper {

    public function toDTO(ExamBlock $model): ExamBlockStudyPlanDTO;
}
