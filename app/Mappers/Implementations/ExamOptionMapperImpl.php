<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Mappers\Implementations;

use App\Mappers\Interfaces\ExamOptionMapper;
use App\Domain\ExamBlockDTO;
use App\Models\ExamBlockOption;
use App\Domain\ExamOptionDTO;

/**
 * Description of ExamOptionMapper
 *
 * @author piero
 */
class ExamOptionMapperImpl  implements ExamOptionMapper{

    public function toDTO(ExamBlockOption $model, ExamBlockDTO $block): ExamOptionDTO {
        $ssd = $model->exam->ssd;
        if($ssd != null){
            $ssd = $ssd->code;
        }
        $newOption = new ExamOptionDTO($model->id, $model->exam->name, $block, $ssd);
        $model->ssds->each(fn($ssd) => $newOption->addCompatibleOption($ssd->code));
        return $newOption;
    }
}
