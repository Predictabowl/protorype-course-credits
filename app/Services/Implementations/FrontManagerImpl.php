<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Models\Front;
use App\Services\Interfaces\FrontManager;
use App\Factories\Interfaces\RepositoriesFactory;
use Illuminate\Support\Collection;
//use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * No Implemented Yet
 */
class FrontManagerImpl implements FrontManager{

    private $blocks;
    private $takenExams;
    //private $examOptions;
    private $repositoriesFactory;

    function __construct(RepositoriesFactory $repositoriesFactory, int $id = 0) {
        $this->repositoriesFactory = $repositoriesFactory;
        if ($id > 0){
            $this->setUp($id);
        }
    }

    public function setFront(int $id): FrontManager {
        $this->setUp($id);
        return $this;
    }

    public function getExamBlocks(): Collection {
        return $this->blocks;
    }

    public function getTakenExams(): Collection {
        return $this->takenExams;
    }


    public function getExamOptions(): Collection {
        return $this->blocks->map(fn(ExamBlockDTO $block) => 
                $block->getExamOptions())->flatten()->unique();
    }

    
    private function setUp(int $id) {
        //$front = Front::find($id)->with("takenExams.ssd");
        //$course = $front->course->first()->with("examBlocks.examBlockOptions.exam")->first();
        
        $this->blocks =  $this->repositoriesFactory->getExamBlockRepository()
                ->getAll($id);
        
        $this->takenExams = $this->repositoriesFactory->getTakenExamRepository()
                ->getAll($id);
        
        //$this->examOptions = $course->examBlocks;//WIP
    }
}
