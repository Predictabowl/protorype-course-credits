<?php

namespace App\Services\Interfaces;

use App\Models\User;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Models\Front;

interface UserFrontManager {

    
    /**
     * Will create a new front.
     * If the front already exist it won't create a new one, but will use
     * the existing one.
     * If the course is specified it ill be set in the front if exists.
     * If the course is not found it will fail and return null.
     * 
     * @param type $courseId
     * @return bool false if it fails
     */
    public function getOrCreateFront($courseId = null): ?Front;
    
    public function getFront(): ?Front;

    public function setUserId($userId): UserFrontManager;
    
    /**
     * Return a managing instance to extract info from the Front of the 
     * current user.
     * If the front doesn't exist will create one without a course.
     * 
     * @param bool $createFront if true will create a Front
     * @return FrontManager
     */
    public function getFrontManager(): ?FrontManager;
    
    /**
     * It will fail and return null if the front is not associated to
     * a Course.
     * Use the FrontManager to set the Course.
     * 
     * @return StudyPlanBuilder|null
     */
    public function getStudyPlanBuilder(): ?StudyPlanBuilder;

}
