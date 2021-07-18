<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Factories\Interfaces\RepositoriesFactory;
use Illuminate\Support\Collection;
use App\Services\Interfaces\FrontInfoManager;
use App\Exceptions\Custom\FrontNotFoundException;

/**
 * It caches the results and always return the cached ones until
 * setFront is called to refresh the queries.
 */
class FrontInfoManagerImpl implements FrontInfoManager{

    private $repositoriesFactory;
    private $id;

    function __construct(RepositoriesFactory $repositoriesFactory, $id) {
        $this->repositoriesFactory = $repositoriesFactory;

        $front = $this->repositoriesFactory->getFrontRepository()->get($id);
        if (!isset($front)){
            throw new FrontNotFoundException("Front not found with id: ".$id);
        }
        $this->id = $id;
    }

    public function getExamBlocks(): Collection {
        return $this->repositoriesFactory->getExamBlockRepository()
                ->getFromFront($this->id);
    }

    public function getTakenExams(): Collection {
        return $this->repositoriesFactory->getTakenExamRepository()
                ->getFromFront($this->id);
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
                ->save($exam, $this->id);
        $this->takenExams = null;
    }

    public function deleteTakenExam($examId) {
        $this->repositoriesFactory->getTakenExamRepository()
                ->delete($examId);
        $this->takenExams = null;
    }

    public function setCourse($courseId): int {
        $front = $this->repositoriesFactory->getFrontRepository()
                ->updateCourse($this->id, $courseId);
        return isset($front) ? 1 : 0;
    }

    public function getActiveFrontId(): ?int {
        return $this->id;
    }

}
