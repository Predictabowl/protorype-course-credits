<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Mappers\Implementations;

use App\Domain\ExamBlockDTO;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Mappers\Interfaces\ExamOptionMapper;
use App\Mappers\Interfaces\ExamBlockMapper;

/**
 * Description of ExamBlockMapperImpl
 *
 * @author piero
 */
class ExamBlockMapperImpl implements ExamBlockMapper{
    
    private $optionMapper;
    
    public function __construct() {
        $this->optionMapper = app()->make(ExamOptionMapper::class);
    }
    
    public function toDTO(ExamBlock $model): ExamBlockDTO {
        $newBlock = new ExamBlockDTO($model->id, $model->max_exams);
        $model->examBlockOptions->map(fn(ExamBlockOption $option) =>  
                $this->optionMapper->toDTO($option, $newBlock));
        return $newBlock;
    }

    
}
