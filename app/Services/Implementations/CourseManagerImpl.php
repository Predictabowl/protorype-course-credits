<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\CourseManager;
use Illuminate\Support\Collection;

/**
 * Description of CourseManagerImpl
 *
 * @author piero
 */
class CourseManagerImpl implements CourseManager {
    
    private $repositoriesFactory;
    private $id;
    
    public function __construct($courseId) {
        $this->id = $courseId;
    }

    public function getExamBlocks(): Collection {
        return $this->repositoriesFactory->getExamBlockRepository()
                ->getFromFront($this->frontId);
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

}
