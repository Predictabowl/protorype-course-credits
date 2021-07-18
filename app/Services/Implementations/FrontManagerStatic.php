<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Services\Interfaces\FrontManager;
use App\Factories\Interfaces\RepositoriesFactory;
use Illuminate\Support\Collection;
use App\Exceptions\Custom\UserNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * It caches the results and always return the cached ones until
 * setFront is called to refresh the queries.
 */
class FrontManagerStatic implements FrontManager{

    private $blocks;
    private $takenExams;
    private $repositoriesFactory;
    private $id;

    function __construct(RepositoriesFactory $repositoriesFactory) {
        $this->repositoriesFactory = $repositoriesFactory;
    }

    public function setFront(int $id): int{
        $this->blocks = null;
        $this->takenExams = null;
        $front = $this->repositoriesFactory->getFrontRepository()->get($id);
        if (!isset($front)){
             $this->id = null;
            return 0;
        }
        $this->id = $id;
        return 1;
    }

    public function getExamBlocks(): Collection {
        if (!isset($this->blocks)){
            $this->blocks =  $this->repositoriesFactory->getExamBlockRepository()
                ->getFromFront($this->id);
        }
        return  $this->blocks;
    }

    public function getTakenExams(): Collection {
        if (!isset($this->takenExams)){
            $this->takenExams = $this->repositoriesFactory->getTakenExamRepository()
                ->getFromFront($this->id);
        }
        return $this->takenExams;
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

    public function deleteTakenExam($id) {
        $this->repositoriesFactory->getTakenExamRepository()
                ->delete($id);
        $this->takenExams = null;
    }

    public function deleteActiveFront(): int {
        throw new Exception("Method deleteActiveFront not implemented yet");
    }

    public function changeCourse($courseId): int {
        $front = $this->repositoriesFactory->getFrontRepository()
                ->updateCourse($this->id, $courseId);
        return isset($front) ? 1 : 0;
    }

    public function createFront($courseId, $userId): int {
        $front = $this->repositoriesFactory->getFrontRepository()
                ->save($courseId, $userId);
        if (!isset($front)){
            return 0;
        }
        $this->id = $front->id;
        return 1;
    }

    public function setFromUser(int $userId): int{
        
        try{
            $front = $this-> repositoriesFactory-> getFrontRepository()
                    ->getFromUser($userId);
            if (!isset($front)){
                return 0;
            }
            $this->id = $front->id;
        } catch (ModelNotFoundException $ex){
            throw new UserNotFoundException($ex->getMessage(),$ex->getCode(),$ex);
        }
        return 1;
    }

    public function getActiveFrontId(): ?int {
        return $this->id;
    }

}
