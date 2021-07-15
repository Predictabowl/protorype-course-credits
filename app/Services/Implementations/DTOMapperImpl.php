<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Implementations;

use App\Services\Interfaces\DTOMapper;
use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;
use App\Domain\TakenExamDTO;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
use App\Models\TakenExam;

/**
 * Description of DTOMapperImpl
 *
 * @author piero
 */
class DTOMapperImpl implements DTOMapper{
    //put your code here
    public function mapExamBlock(ExamBlock $block): ExamBlockDTO {
        return new ExamBlockDTO($block->id, $block->max_exams);
    }

    public function mapExamOption(ExamBlockOption $option, ExamBlockDTO $block): ExamOptionDTO {
        $newOption = new ExamOptionDTO($option->id, $option->exam->name, $block, $option->exam->cfu, $option->exam->ssd->code);
        $option->ssds->each(fn($ssd) => $newOption->addCompatibleOption($ssd->code));
        return $newOption;
    }

    public function mapTakenExam(TakenExam $exam): TakenExamDTO {
        return new TakenExamDTO($exam->id, $exam->name, $exam->ssd->code, $exam->cfu);
    }

}
