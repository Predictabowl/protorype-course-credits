<?php

namespace App\Mappers\Implementations;

use App\Models\Ssd;
use App\Models\Front;
use App\Models\TakenExam;
use App\Domain\TakenExamDTO;
use App\Mappers\Interfaces\TakenExamMapper;

/**
 * Description of TakenExamMapper
 *
 * @author piero
 */


class TakenExamMapperImpl implements TakenExamMapper{
    
    
    public function toModel(TakenExamDTO $dto, $frontId): ?TakenExam{
        $ssd = Ssd::firstWhere("code",$dto->getSsd());
        if (!isset($ssd)){
            return null;
        }

        return TakenExam::make([
            "name" => $dto->getExamName(),
            "cfu" => $dto->getCfu(),
            "ssd_id" => $ssd->id,
            "front_id" => $frontId
        ]);
    }
    
    public function toDTO(TakenExam $model): TakenExamDTO {
        return new TakenExamDTO($model->id, $model->name, $model->ssd->code, $model->cfu);
    }
    
    
}
