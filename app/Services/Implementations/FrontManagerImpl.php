<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Implementations;

use App\Models\Front;
use App\Services\Interfaces\FrontManager;
use App\Domain\TakenExamDTO;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Repositories\Interfaces\CourseRepository;
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
    
    public function saveTakenExam($attributes) {
        $exam = new TakenExamDTO(0, $attributes["name"], $attributes["ssd"], $attributes["cfu"]);
        $takenExam = $this->mapper->toModel($exam, $this->frontId);
        if(!isset($takenExam)){
            throw new InvalidArgumentException();//message missing
        }
        $this->getExamRepository()->save($takenExam);
    }

    public function deleteTakenExam($examId) {
        $repo = $this->getExamRepository();
        $takenExam = $repo->get($examId);
        if ($takenExam->front_id === $this->frontId){ //extra check, not strictly needed
                $repo->delete($examId);
        }
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

    public function getFront(): Front {
        return $this->getFrontRepository()->get($this->frontId);
    }

    public function getCourses(): Collection {
        return app()->make(CourseRepository::class)->getAll();
    }

}
