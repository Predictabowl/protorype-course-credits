<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockStudyPlanDTO;
use App\Mappers\Interfaces\ExamBlockMapper;
use App\Models\Course;
use App\Services\Interfaces\CourseDataBuilder;
use Illuminate\Support\Collection;
use function collect;

/**
 * Description of CourseManagerImpl
 *
 * @author piero
 */
class CourseDataBuilderImpl implements CourseDataBuilder {
    
    private Course $course;
    private ExamBlockMapper $blockMapper;
    private Collection $examBlocksDTO;
    
    public function __construct(
            Course $course,
            ExamBlockMapper $blockMapper){
        $this->blockMapper = $blockMapper;
        $this->examBlocksDTO = collect([]);
        $this->course = $course;
        $this->buildExamBlocks();
    }

    public function getExamBlocks(): Collection {
        return $this->examBlocksDTO;
    }

    public function getExamOptions(): Collection {
        $options = $this->getExamBlocks()->map(fn(ExamBlockStudyPlanDTO $block) =>
                $block->getExamOptions());
        if (isset($options)){
            $options = $options->flatten()->unique();
        } else {
            $options = collect([]);
        }
        return $options;
    }
    
    private function buildExamBlocks() {
         $this->examBlocksDTO = $this->course->examBlocks
                ->map(fn($block) => $this->blockMapper->toDTO($block));
    }

    public function getCourse(): Course{
        return $this->course;
    }

}
