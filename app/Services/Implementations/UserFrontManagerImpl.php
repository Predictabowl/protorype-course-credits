<?php

namespace App\Services\Implementations;

use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Models\Front;
use App\Repositories\Interfaces\FrontRepository;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Services\Interfaces\UserFrontManager;
use Illuminate\Support\Facades\DB;


class UserFrontManagerImpl implements UserFrontManager{

    private int $userId;
    private FrontManager $frontManager;
    private FrontRepository $frontRepo;
    private StudyPlanBuilderFactory $spbFactory;

    public function __construct(
            FrontRepository $frontRepo,
            FrontManager $frontManager,
            StudyPlanBuilderFactory $spbFactory,
            int $userId)
    {
        $this->userId = $userId;
        $this->frontRepo = $frontRepo;
        $this->frontManager = $frontManager;
        $this->spbFactory = $spbFactory;
    }

    public function getOrCreateFront($courseId = null): Front{
        return DB::transaction(function() use($courseId){
            $front = $this->frontRepo->getFromUser($this->userId);
            if (isset($front)){
                return $this->getUpdatedFront($front, $courseId);
            }

            $newFront = new Front([
                "user_id" => $this->userId,
                "course_id" => $courseId
            ]);

            $createdFront = $this->frontRepo->save($newFront);
            return isset($createdFront) ? $createdFront : null;
        });
    }

    public function getFront(): ?Front {
        return $this->frontRepo->getFromUser($this->userId);
    }

    public function getFrontManager(): ?FrontManager {
        $front = $this->getOrCreateFront();
        if(!isset($front)){
            return null;
        }
        return $this->frontManager;
    }

    public function getStudyPlanBuilder(): ?StudyPlanBuilder {
        $front = $this->getOrCreateFront();
        if (!isset($front) || !isset($front->course_id)){
            return null;
        }
        return $this->spbFactory
                ->get($front->id, $front->course_id);
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
