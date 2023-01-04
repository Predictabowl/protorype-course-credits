<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockStudyPlanDTO;
use App\Mappers\Interfaces\ExamBlockMapper;
use App\Models\Course;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
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
    private ExamBlockRepository $blockRepo;
    private CourseRepository $courseRepo;
    
    public function __construct(
            $courseId,
            ExamBlockMapper $blockMapper,
            ExamBlockRepository $blockRepo,
            CourseRepository $courseRepo)
    {
        $this->courseId = $courseId;
        $this->blockMapper = $blockMapper;
        $this->blockRepo = $blockRepo;
        $this->courseRepo = $courseRepo;
    }

    public function getExamBlocks(): Collection {
        return $this->blockRepo
                ->getFilteredByCourse($this->courseId)
                ->map(fn($block) => $this->blockMapper->toDTO($block));
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
    
    public function getCourse(): Course {
        return $this->courseRepo->get($this->courseId);
    }

}
