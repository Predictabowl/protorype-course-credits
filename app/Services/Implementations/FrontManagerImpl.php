<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Implementations;

use App\Domain\TakenExamDTO;
use App\Mappers\Interfaces\TakenExamMapper;
use App\Models\Front;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Services\Interfaces\FrontManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Webmozart\Assert\InvalidArgumentException;

/**
 * Description of FrontManagerImpl
 *
 * @author piero
 */
class FrontManagerImpl implements FrontManager{

    private $frontId;
    private TakenExamMapper $mapper;
    private TakenExamRepository $takenExamRepo;
    private FrontRepository $frontRepo;
    private CourseRepository $courseRepo;

    public function __construct($frontId, TakenExamMapper $mapper,
            TakenExamRepository $takenExamRepo, FrontRepository $frontRepo,
            CourseRepository $courseRepo) {
        $this->mapper = $mapper;
        $this->takenExamRepo = $takenExamRepo;
        $this->frontRepo = $frontRepo;
        $this->courseRepo = $courseRepo;
        $this->frontId = $frontId;
    }

    public function getTakenExams(): Collection {
        $exams = $this->takenExamRepo->getFromFront($this->frontId);
        return $exams->map(
                fn($exam) => $this->mapper->toDTO($exam));
    }

    public function saveTakenExam($attributes) {
        $exam = new TakenExamDTO(0, $attributes["name"], $attributes["ssd"],
                $attributes["cfu"], $attributes["grade"]);
        $takenExam = $this->mapper->toModel($exam, $this->frontId);
        if(!isset($takenExam)){
            throw new InvalidArgumentException();//message missing
        }
        DB::transaction(function() use($takenExam){
            $this->takenExamRepo->save($takenExam);
        });
    }

    public function deleteTakenExam($examId) {
        DB::transaction(function() use($examId){
            $this->takenExamRepo->delete($examId);
        });
    }

    public function setCourse($courseId): bool {
        $front = $this->frontRepo->updateCourse($this->frontId, $courseId);
        return isset($front) ? true : false;
    }

    public function getFront(): Front {
        return $this->frontRepo->get($this->frontId);
    }

    public function deleteAllTakenExams() {
        DB::transaction(function(){
            $this->takenExamRepo->deleteFromFront($this->frontId);
        });
    }

}
