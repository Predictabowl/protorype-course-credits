<?php

namespace App\Services\Interfaces;

use Illuminate\Support\Collection;
use App\Services\Interfaces\FrontInfoManager;

interface UserFrontManager {

    
    /**
     * Create a new front.
     * If the front already exist it won't create a new one, but will use
     * the existing one changing the course.
     * If $courseId is not specified will create one without an associated
     * course.
     * 
     * @param type $courseId
     * @return int the id of the front
     */
    public function createFront($courseId): int;

    public function deleteActiveFront(): int;

    public function getFrontId(): ?int;
    
    /**
     * Return a managing instance to extract info from the Front of the 
     * current user.
     * Return null if the Front doesn't exists.
     * 
     * @return FrontInfoManager|null
     */
    public function getFrontInfoManager(): ?FrontInfoManager;
}
