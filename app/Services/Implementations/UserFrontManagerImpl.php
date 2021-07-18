<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use \App\Models\Front;
use App\Factories\Interfaces\RepositoriesFactory;
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

    function __construct($userId) {
        $this->setUser($userId, $create);
    }

  
    public function setCourse($courseId): int {
        $front = $this->repositoriesFactory->getFrontRepository()
                ->updateCourse($this->id, $courseId);
        return isset($front) ? 1 : 0;
    }

    public function createFront($courseId = null): int {
        $front = $this->repositoriesFactory->getFrontRepository()
                ->save($courseId, $userId);
        if (!isset($front)){
            return 0;
        }
        $this->id = $front->id;
        return 1;
    }

    public function setUser(int $userId, bool $create = true): int{
        $repo = $this->repositoriesFactory->getFrontRepository();
        try{
            $front = $repo->getFromUser($userId);
            if (!isset($front)){
                if ($create){
                    $front = $repo->save(new Front(["user_id" => $userId]));
                } else {
                    return 0;
                }
            }
            $this->id = $front->id;
            return 1;
        } catch (ModelNotFoundException $ex){
            throw new UserNotFoundException($ex->getMessage(),$ex->getCode(),$ex);
        }
    }

    public function getActiveFrontId(): ?int {
        return $this->id;
    }

    public function deleteActiveFront(): int {
        
    }

    public function getFrontId(): ?int {
        
    }

    public function getFrontInfoManager(): ?FrontInfoManager {
        
    }

}
