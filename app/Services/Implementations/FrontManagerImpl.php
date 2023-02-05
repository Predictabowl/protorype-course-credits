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

    private TakenExamMapper $mapper;
    private TakenExamRepository $takenExamRepo;
    private FrontRepository $frontRepo;
    private CourseRepository $courseRepo;

    public function __construct(TakenExamMapper $mapper,
            TakenExamRepository $takenExamRepo,
            FrontRepository $frontRepo,
            CourseRepository $courseRepo) {
        $this->mapper = $mapper;
        $this->takenExamRepo = $takenExamRepo;
        $this->frontRepo = $frontRepo;
        $this->courseRepo = $courseRepo;
    }

    public function getTakenExams(int $frontId): Collection {
        $exams = $this->takenExamRepo->getFromFront($frontId);
        return $exams->map(
                fn($exam) => $this->mapper->toDTO($exam));
    }

    public function saveTakenExam($attributes, int $frontId) {
        $exam = new TakenExamDTO(0, $attributes["name"], $attributes["ssd"],
                $attributes["cfu"], $attributes["grade"]);
        $takenExam = $this->mapper->toModel($exam, $frontId);
        if(!isset($takenExam)){
            throw new InvalidArgumentException();//message missing
        }
        DB::transaction(function() use($takenExam){
            $this->takenExamRepo->save($takenExam);
        });
    }

    public function deleteTakenExam(int $examId) {
        DB::transaction(function() use($examId){
            $this->takenExamRepo->delete($examId);
        });
    }

    public function setCourse(int $frontId, int $courseId): bool {
        return DB::transaction(function() use($frontId, $courseId){
            $front = $this->frontRepo->updateCourse($frontId, $courseId);
            return isset($front) ? true : false;
        });
    }

    public function getFront(int $frontId): ?Front {
        return $this->frontRepo->get($frontId);
    }

    public function deleteAllTakenExams(int $frontId) {
        DB::transaction(function() use($frontId){
            $this->takenExamRepo->deleteFromFront($frontId);
        });
    }

    public function getOrCreateFront(int $userId, ?int $courseId = null): Front {
        return DB::transaction(function() use($userId, $courseId){
            $front = $this->frontRepo->getFromUser($userId);
            if(isset($front)){
                return $this->updateFront($front, $courseId);
            }

            return $this->createFront($userId, $courseId);
        });
        
    }

    private function createFront(int $userId, ?int $courseId): Front{
        $newFront = new Front([
            "user_id" => $userId,
            "course_id" => $courseId
        ]);
        return $this->frontRepo->save($newFront);
    }
    
    private function updateFront(Front $front, ?int $courseId): Front{
        if (isset($courseId) && $front->course_id != $courseId){
            return $this->frontRepo->updateCourse($front->id, $courseId);
        }
        return $front;
    }
}
