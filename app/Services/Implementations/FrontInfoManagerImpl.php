<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Factories\Interfaces\RepositoriesFactory;
use Illuminate\Support\Collection;
use App\Services\Interfaces\FrontInfoManager;

/**
 * It caches the results and always return the cached ones until
 * setFront is called to refresh the queries.
 */
class FrontInfoManagerImpl implements FrontInfoManager{

    private $repositoriesFactory;
    private $frontId;

    function __construct(RepositoriesFactory $repositoriesFactory, $frontId) {
        $this->repositoriesFactory = $repositoriesFactory;
        $this->frontId = $frontId;
    }

    public function getExamBlocks(): Collection {
        return $this->repositoriesFactory->getExamBlockRepository()
                ->getFromFront($this->frontId);
    }

    public function getTakenExams(): Collection {
        return $this->repositoriesFactory->getTakenExamRepository()
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

    public function saveTakenExam(TakenExamDTO $exam) {
        $this->repositoriesFactory->getTakenExamRepository()
                ->save($exam, $this->frontId);
    }

    public function deleteTakenExam($examId) {
        $this->repositoriesFactory->getTakenExamRepository()
                ->delete($examId);
    }

    public function setCourse($courseId): int {
        $front = $this->repositoriesFactory->getFrontRepository()
                ->updateCourse($this->frontId, $courseId);
        return isset($front) ? 1 : 0;
    }

    public function getActiveFrontId(): ?int {
        return $this->frontId;
    }

}
