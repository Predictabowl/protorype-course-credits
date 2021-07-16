<?php

namespace App\Repositories\Implementations;

use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;
use App\Models\ExamBlockOption;
use App\Models\ExamBlock;
use App\Models\Front;
use App\Repositories\Interfaces\ExamBlockRepository;
use Illuminate\Support\Collection;
/**
 * Description of ExamBlockRepositoryImpl
 *
 * @author piero
 */
class ExamBlockRepositoryImpl implements ExamBlockRepository{
    
    public function get($id): ExamBlockDTO {
        $block = ExamBlock::with("examBlockOptions.exam.ssd")->find($id);
        return $this->mapExamBlock($block);
    }

    public function getFromFront($frontId): Collection {
        $front = Front::with("course.examBlocks.examBlockOptions.exam.ssd")->find($frontId);
        return $front->course->examBlocks->map(fn($block) => $this->mapExamBlock($block));
    }
    
    public function mapExamBlock(ExamBlock $block): ExamBlockDTO {
        $newBlock = new ExamBlockDTO($block->id, $block->max_exams);
        $block ->examBlockOptions->map(fn($option) =>  
                $this->mapExamOption($option, $newBlock));
        return $newBlock;
    }

    private function mapExamOption(ExamBlockOption $option, ExamBlockDTO $block): ExamOptionDTO {
        $newOption = new ExamOptionDTO($option->id, $option->exam->name, $block, $option->exam->cfu, $option->exam->ssd->code);
        $option->ssds->each(fn($ssd) => $newOption->addCompatibleOption($ssd->code));
        return $newOption;
    }
}
