<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockStudyPlanDTO;
use App\Mappers\Interfaces\ExamBlockMapper;
use App\Models\Course;
use App\Services\Interfaces\CourseAdminManager;
use App\Services\Interfaces\CourseManager;
use Illuminate\Support\Collection;
use function collect;

/**
 * Description of CourseManagerImpl
 *
 * @author piero
 */
class CourseManagerImpl implements CourseManager {
    
    private ExamBlockMapper $blockMapper;
    private $courseId;
    private CourseAdminManager $courseAdminManager;
    private ?Course $course;
    private ?Collection $examBlocksDTO;
    
    public function __construct(
            $courseId,
            ExamBlockMapper $blockMapper,
            CourseAdminManager $courseAdminManager)
    {
        $this->courseId = $courseId;
        $this->blockMapper = $blockMapper;
        $this->courseAdminManager = $courseAdminManager;
        $this->course = null;
        $this->examBlocksDTO = null;
    }

    public function getExamBlocks(bool $cached = true): Collection {
        if (is_null($this->examBlocksDTO) || !$cached){
            $this->examBlocksDTO = $this->getCourse($cached)->examBlocks
                ->map(fn($block) => $this->blockMapper->toDTO($block));
        }
        return $this->examBlocksDTO;
    }

    public function getExamOptions(bool $cached = true): Collection {
        $options = $this->getExamBlocks($cached)->map(fn(ExamBlockStudyPlanDTO $block) =>
                $block->getExamOptions());
        if (isset($options)){
            $options = $options->flatten()->unique();
        } else {
            $options = collect([]);
        }
        return $options;
    }
    
    public function getCourse(bool $cached = true): Course {
        if(is_null($this->course) || !$cached) {
            $this->course = $this->courseAdminManager
                    ->getCourseFullData($this->courseId);
        }
        return $this->course;
    }

}
