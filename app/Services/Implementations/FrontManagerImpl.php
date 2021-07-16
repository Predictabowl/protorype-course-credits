<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockDTO;
use App\Services\Interfaces\FrontManager;
use App\Factories\Interfaces\RepositoriesFactory;
use Illuminate\Support\Collection;
//use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * On every call it uses the repositories to make new queries,
 * so care must be used if used on static content as will make a lot
 * of useless queries.
 */

class FrontManagerImpl implements FrontManager{

    private $repositoriesFactory;
    private $frontId;

    function __construct(RepositoriesFactory $repositoriesFactory, int $frontId = 0) {
        $this->repositoriesFactory = $repositoriesFactory;
        $this->frontId = $frontId;
    }

    public function setFront(int $id): FrontManager {
        $this->frontId = $id;
        return $this;
    }

    public function getExamBlocks(): Collection {
        return  $this->repositoriesFactory->getExamBlockRepository()
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
}
