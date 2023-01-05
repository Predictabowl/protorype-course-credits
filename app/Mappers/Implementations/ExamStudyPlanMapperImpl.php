<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Mappers\Implementations;

use App\Domain\ExamBlockStudyPlanDTO;
use App\Domain\ExamStudyPlanDTO;
use App\Mappers\Interfaces\ExamStudyPlanMapper;
use App\Models\Exam;

/**
 * Description of ExamOptionMapper
 *
 * @author piero
 */
class ExamStudyPlanMapperImpl  implements ExamStudyPlanMapper{

    public function toDTO(Exam $model, ExamBlockStudyPlanDTO $block): ExamStudyPlanDTO {
        $ssd = $model->ssd;
        if($ssd != null){
            $ssd = $ssd->code;
        }
        $newOption = new ExamStudyPlanDTO($model->id, $model->name, $block, $ssd);
//        $model->ssds->each(fn($ssd) => $newOption->addCompatibleOption($ssd->code));
        return $newOption;
    }
}
