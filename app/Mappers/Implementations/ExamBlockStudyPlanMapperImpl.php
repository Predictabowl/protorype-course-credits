<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Mappers\Implementations;

use App\Domain\ExamBlockStudyPlanDTO;
use App\Mappers\Interfaces\ExamBlockMapper;
use App\Mappers\Interfaces\ExamStudyPlanMapper;
use App\Models\Exam;
use App\Models\ExamBlock;

/**
 * Description of ExamBlockMapperImpl
 *
 * @author piero
 */
class ExamBlockStudyPlanMapperImpl implements ExamBlockMapper{
    
    private ExamStudyPlanMapper $examMapper;
    
    public function __construct(ExamStudyPlanMapper $examMapper) {
        $this->examMapper = $examMapper;
    }
    
    public function toDTO(ExamBlock $model): ExamBlockStudyPlanDTO {
        $newBlock = new ExamBlockStudyPlanDTO(
                $model->id,
                $model->max_exams,
                $model->cfu,
                $model->courseYear);
        $model->exams->map(fn(Exam $option) =>  
                $this->examMapper->toDTO($option, $newBlock));
        $model->ssds->each(fn($ssd) => $newBlock->addCompatibleOption($ssd->code));
        return $newBlock;
    }
    
}
