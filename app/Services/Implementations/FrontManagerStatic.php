<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Services\Interfaces\FrontManager;
use App\Factories\Interfaces\RepositoriesFactory;
use Illuminate\Support\Collection;
use App\Exceptions\Custom\FrontNotFoundException;
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

    public function setFront(int $id): FrontManager{
        $front = $this->repositoriesFactory->getFrontRepository()->get($id);
         if (!isset($front)){
            throw new FrontNotFoundException("Could not find Front with id: ".$id);
        }
        $this->id = $id;
        $this->blocks = null;
        $this->takenExams = null;
        return $this;
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

    public function delete($id): int {
        
    }

    public function changeCourse($courseId): int {
        
    }

    public function createFront($courseId, $userId): int {
        
    }

    public function setFromUser(int $userId): int{
        
        try{
            $front = $this-> repositoriesFactory-> getFrontRepository()
                    ->getFromUser($userId);
            if (!isset($front)){
                return 0;
            }
        } catch (ModelNotFoundException $ex){
            throw new UserNotFoundException($ex->getMessage(),$ex->getCode(),$ex);
        }
        return 1;
    }

}
