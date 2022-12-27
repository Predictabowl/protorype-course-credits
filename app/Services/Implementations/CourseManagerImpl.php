<?php

namespace App\Services\Implementations;

use App\Models\Course;
use App\Domain\ExamBlockDTO;
use App\Services\Interfaces\CourseManager;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\CourseRepository;
use App\Mappers\Interfaces\ExamBlockMapper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

/**
 * Description of CourseManagerImpl
 *
 * @author piero
 */
class CourseManagerImpl implements CourseManager {
    
    private $blockMapper;
    private $courseId;
    
    public function __construct($courseId, $blockMapper) {
        $this->courseId = $courseId;
        $this->blockMapper = $blockMapper;
    }

    public function getExamBlocks(): Collection {
        return $this->getBlockRepository()
                ->getFilteredByCourse($this->courseId)
                ->map(fn($block) => $this->blockMapper->toDTO($block));
    }

    public function getExamOptions(): Collection {
        $options = $this->getExamBlocks()->map(fn(ExamBlockDTO $block) => 
                $block->getExamOptions());
        if (isset($options)){
            $options = $options->flatten()->unique();
        } else {
            $options = collect([]);
        }
        return $options;
    }
    
    public function getCourse(): Course {
        return app()->make(CourseRepository::class)->get($this->courseId);
    }
    
    private function getBlockRepository(): ExamBlockRepository {
        return app()->make(ExamBlockRepository::class);
    }

}
