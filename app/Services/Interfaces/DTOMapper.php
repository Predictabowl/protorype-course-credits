<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Interfaces;

use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\TakenExam;

/**
 *
 * @author piero
 */
interface DTOMapper {
    
    public function mapExamBlock(ExamBlock $block): ExamBlockDTO;
    
    public function mapExamOption(ExamBlockOption $option, ExamBlockDTO $block): ExamOptionDTO;
    
    public function mapTakenExam(TakenExam $exam): TakenExamDTO;
}
