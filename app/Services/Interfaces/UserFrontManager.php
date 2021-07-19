<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\FrontInfoManager;
use App\Models\Front;

interface UserFrontManager {

    
    /**
     * Create a new front.
     * If the front already exist it won't create a new one, but will use
     * the existing one changing the course.
     * If the course is not found it will fail.
     * 
     * @param type $courseId
     * @return bool false if it fails
     */
    public function createFront($courseId = null): ?Front;
    
    public function deleteFront(): bool;

    public function getFront(): ?Front;
    
//    public function setCourse($courseId): bool;
    
    /**
     * Return a managing instance to extract info from the Front of the 
     * current user.
     * Return null if the Front doesn't exists.
     * 
     * @param bool $createFront if true will create a Front
     * @return FrontInfoManager|null
     */
    public function getFrontInfoManager(): ?FrontInfoManager;
}
