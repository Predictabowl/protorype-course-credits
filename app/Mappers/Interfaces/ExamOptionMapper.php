<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Mappers\Interfaces;

use App\Models\ExamBlockOption;
use App\Domain\ExamOptionDTO;
use App\Domain\ExamBlockDTO;

/**
 *
 * @author piero
 */
interface ExamOptionMapper {

    public function toDTO(ExamBlockOption $model, ExamBlockDTO $block): ExamOptionDTO;
}
