<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockDTO;
use App\Services\Interfaces\CourseManager;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Mappers\Interfaces\ExamBlockMapper;
use Illuminate\Support\Collection;

/**
 * Description of CourseManagerImpl
 *
 * @author piero
 */
class CourseManagerImpl implements CourseManager {
    
    private $blockMapper;
    private $id;
    
    public function __construct($courseId) {
        $this->id = $courseId;
        $this->blockMapper = app()->make(ExamBlockMapper::class);
    }

    public function getExamBlocks(): Collection {
        return $this->getBlockRepository()
                ->getFromFront($this->id)
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
    
    private function getBlockRepository(): ExamBlockRepository {
        return app()->make(ExamBlockRepository::class);
    }

}
