<?php

namespace App\Services\Implementations;

use \App\Models\Front;
use App\Factories\Interfaces\FrontManagerFactory;
use App\Repositories\Interfaces\FrontRepository;
use \App\Services\Interfaces\UserFrontManager;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Factories\Interfaces\StudyPlanBuilderFactory;
use Illuminate\Support\Facades\DB;


class UserFrontManagerImpl implements UserFrontManager{

    private int $userId;
    private FrontManagerFactory $fmFactory;
    private FrontRepository $frontRepo;
    private StudyPlanBuilderFactory $spbFactory;

    public function __construct(
            FrontRepository $frontRepo,
            FrontManagerFactory $fmFactory,
            StudyPlanBuilderFactory $spbFactory,
            int $userId)
    {
        $this->userId = $userId;
        $this->frontRepo = $frontRepo;
        $this->fmFactory = $fmFactory;
        $this->spbFactory = $spbFactory;
    }

    public function getOrCreateFront($courseId = null): ?Front{
        $transaction = DB::transaction(function() use($courseId){
            $front = $this->frontRepo->getFromUser($this->userId);
            if (isset($front)){
                return $this->getUpdatedFront($front, $courseId);
            }

            $front = new Front([
                "user_id" => $this->userId,
                "course_id" => $courseId
            ]);

            $front = $this->frontRepo->save($front);
            return isset($front) ? $front : null;
        });
        return $transaction;
    }

    public function getFront(): ?Front {
        return $this->frontRepo->getFromUser($this->userId);
    }

    public function getFrontManager(): ?FrontManager {
        $front = $this->getOrCreateFront();
        if(!isset($front)){
            return null;
        }
        return $this->fmFactory->getFrontManager($front->id);
    }

    public function getStudyPlanBuilder(): ?StudyPlanBuilder {
        $front = $this->getOrCreateFront();
        if (!isset($front) || !isset($front->course_id)){
            return null;
        }
        return $this->spbFactory
                ->getStudyPlanBuilder($front->id, $front->course_id);
    }

    private function getUpdatedFront(Front $front, $courseId): ?Front{
        if (isset($courseId) && $front->course_id != $courseId){
            $front = $this->frontRepo->updateCourse($front->id, $courseId);
        }
        return $front;
    }

    public function getUserId(): int {
        return $this->userId;
    }

}
