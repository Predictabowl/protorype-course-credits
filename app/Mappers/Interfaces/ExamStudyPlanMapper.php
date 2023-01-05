<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Mappers\Interfaces;

use App\Domain\ExamBlockStudyPlanDTO;
use App\Domain\ExamStudyPlanDTO;
use App\Models\Exam;

/**
 *
 * @author piero
 */
interface ExamStudyPlanMapper {

    public function toDTO(Exam $model, ExamBlockStudyPlanDTO $block): ExamStudyPlanDTO;
}
