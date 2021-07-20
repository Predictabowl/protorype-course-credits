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
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Mappers\Interfaces\TakenExamMapper;

/**
 * Description of FrontManagerImpl
 *
 * @author piero
 */
class FrontManagerImpl implements FrontManager{
    
    private $frontId;
    private $mapper;

    function __construct($frontId) {
        $this->mapper = app()->make(TakenExamMapper::class);
        $this->frontId = $frontId;
    }

    public function getTakenExams(): Collection {
        $exams = $this->getExamRepository()
                ->getFromFront($this->frontId);
        return $exams->map(
                fn($exam) => $this->mapper->toDTO($exam));
    }
    
    public function saveTakenExam(TakenExamDTO $exam) {
        $this->getExamRepository()
                ->save($this->mapper->toModel($exam, $this->frontId));
    }

    public function deleteTakenExam($examId) {
        $this->getExamRepository()->delete($examId);
    }

    public function setCourse($courseId): bool {
        $front = $this->getFrontRepository()->updateCourse($this->frontId, $courseId);
        return isset($front) ? true : false;
    }
    
    private function getExamRepository(): TakenExamRepository{
        return app()->make(TakenExamRepository::class);
    }
    
    private function getFrontRepository(): FrontRepository{
        return app()->make(FrontRepository::class);
    }

}
