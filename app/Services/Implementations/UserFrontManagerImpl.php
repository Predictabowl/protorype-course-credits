<?php

namespace App\Services\Implementations;

use App\Models\User;
use \App\Models\Front;
use App\Factories\Interfaces\FrontManagerFactory;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Interfaces\CourseRepository;
use \App\Services\Interfaces\UserFrontManager;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Factories\Interfaces\StudyPlanBuilderFactory;


class UserFrontManagerImpl implements UserFrontManager{

    private $userId;

    function __construct($userId = null) {
        if (isset($userId)){
            $this->userId = $userId;
        } else {
            $this->userId = auth()->user()->id;
        }
    }

    public function getOrCreateFront($courseId = null): ?Front{
        $frontRepo = $this->getFrontRepository();
        $front = $frontRepo->getFromUser($this->userId);
        if (isset($front)){
            if (isset($courseId) && $front->course_id != $courseId){
                $front = $frontRepo->updateCourse($front->id, $courseId);
            }
            return $front;
        }
        
        $front = new Front([
            "user_id" => $this->userId,
            "course_id" => $courseId
        ]);       
        
        $front = $frontRepo->save($front);
        return isset($front) ? $front : null;
    }

    public function getFront(): ?Front {
        return $this->getFrontRepository()->getFromUser($this->userId);
    }
    
    public function getFrontManager(): ?FrontManager {
        $front = $this->getOrCreateFront();
        if(!isset($front)){
            return null;
        }
        return app()->make(FrontManagerFactory::class)->getFrontManager($front->id);
    }

    public function getStudyPlanBuilder(): ?StudyPlanBuilder {
        $front = $this->getOrCreateFront();
        if (!isset($front) || !isset($front->course_id)){
            return null;
        }
        return app()->make(StudyPlanBuilderFactory::class)
                ->getStudyPlanBuilder($front->id, $front->course_id);
    }

    public function setUserId($userId): UserFrontManager {
        $this->userId = $userId;
        return $this;
    }

    
    
    private function getFrontRepository(): FrontRepository{
        return app()->make(FrontRepository::class);
    }

}
