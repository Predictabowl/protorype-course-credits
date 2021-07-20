<?php

namespace App\Services\Implementations;

use \App\Models\Front;
use App\Factories\Interfaces\RepositoriesFactory;
use \App\Services\Interfaces\UserFrontManager;
use App\Services\Interfaces\FrontInfoManager;
use App\Factories\Interfaces\FrontInfoManagerFactory;


class UserFrontManagerImpl implements UserFrontManager{

    private $user;
    private $repoFactory;
    private $infoFactory;

    function __construct(RepositoriesFactory $repoFactory,
            FrontInfoManagerFactory $infoFactory) {
        $this->repoFactory = $repoFactory;
        $this->infoFactory = $infoFactory;
        $this->user = auth()->user();
    }

    public function createFront($courseId = null): ?Front{
        $courseRepo = $this->repoFactory->getCourseRepository();
        $course = $courseRepo->get($courseId);
        if (!isset($course)){
            return false;
        }
        
        $frontRepo = $this->repoFactory->getFrontRepository();
        
        $front = new Front([
            "user_id" => $this->user->id,
            "course_id" => $courseId
        ]);
        $front = $frontRepo->save($front);
        return isset($front) ? true : false;
    }

    public function deleteFront(): bool {
        $repo = $this->repoFactory->getFrontRepository();
        $front = $repo->getFromUser($this->user->id);
        if (!isset($front)){
            return false;
        }
        return ($repo->delete($front->id) == 0) ? false : true;
    }

    public function getFront(): ?Front {
        return $this->repoFactory->getFrontRepository()->getFromUser($this->user->id);
    }

    public function getFrontInfoManager(): ?FrontInfoManager {
        $front = $this->repoFactory->getFrontRepository()->getFromUser($this->user->id);
        return isset($front) ? $this->infoFactory->getInstance($front->id) : null;
    }

}
