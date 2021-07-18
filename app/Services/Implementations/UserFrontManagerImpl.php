<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use \App\Models\Front;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\UserRepository;
use App\Repositories\Interfaces\FrontRepository;
use Illuminate\Support\Collection;
use App\Exceptions\Custom\UserNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \App\Services\Interfaces\UserFrontManager;
use App\Services\Interfaces\FrontInfoManager;

/**
 * It caches the results and always return the cached ones until
 * setFront is called to refresh the queries.
 */
class UserFrontManagerImpl implements UserFrontManager{

    private $id;
    private $factory;

    function __construct($userId, RepositoriesFactory $factory) {
        $this->factory = $factory;
        $this->id = $userId;
    }

    public function createFront($courseId): bool {
        $courseRepo = $this->factory->getCourseRepository();
        $course = $courseRepo->get($courseId);
        if (!isset($course)){
            return false;
        }
        $frontRepo = $this->factory->getFrontRepository();
        
        $front = new Front([
            "user_id" => $this->id,
            "course_id" => $courseId
        ]);
        $front = $frontRepo->save($front);
        return isset($front) ? true : false;
    }

    public function deleteActiveFront(): int {
        
    }

    public function getFrontId(): ?int {
        $front = $this->factory->getFrontRepository()->getFromUser($this->id);
        return isset($front)? $front->id : null; 
    }

    public function getFrontInfoManager(): ?FrontInfoManager {
        
    }

}
