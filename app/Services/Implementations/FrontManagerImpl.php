<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Implementations;

use App\Services\Interfaces\FrontManager;
use App\Models\TakenExam;
use App\Domain\TakenExamDTO;
use Illuminate\Support\Collection;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Mappers\Interfaces\TakenExamMapper;

/**
 * Description of FrontManagerImpl
 *
 * @author piero
 */
class FrontManagerImpl implements FrontManager{
    
    private $repositoriesFactory;
    private $frontId;
    private $mapper;

    function __construct($frontId) {
        $this->repositoriesFactory = app()->make(RepositoriesFactory::class);
        $this->mapper = app()->make(TakenExamMapper::class);
        $this->frontId = $frontId;
    }

    public function getTakenExams(): Collection {
        $exams = $this->repositoriesFactory->getTakenExamRepository()
                ->getFromFront($this->frontId);
        return $exams->map(
                fn($exam) => $this->mapper->toDTO($exam));
    }
    
    public function saveTakenExam(TakenExamDTO $exam) {
        $this->repositoriesFactory->getTakenExamRepository()
                ->save($this->mapper->toModel($exam, $this->frontId));
    }

    public function deleteTakenExam($examId) {
        $this->repositoriesFactory->getTakenExamRepository()
                ->delete($examId);
    }


}
