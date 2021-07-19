<?php

namespace App\Mappers\Interfaces;

use App\Models\TakenExam;
use App\Domain\TakenExamDTO;

/**
 * Description of TakenExamMapper
 *
 * @author piero
 */


interface TakenExamMapper {
    
    public function toModel(TakenExamDTO $dto, $frontId): ?TakenExam;
    
    public function toDTO(TakenExam $model): TakenExamDTO;
    
}
