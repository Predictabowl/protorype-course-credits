<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\ManagersFactory;
use App\Services\Interfaces\CourseManager;
use App\Services\Implementations\CourseManagerImpl;
use App\Services\Interfaces\FrontManager;
use App\Services\Implementations\FrontManagerImpl;

/**
 * Description of ManagersFactoryImpl
 *
 * @author piero
 */
class ManagersFactoryImpl implements ManagersFactory{
    
    public function getCourseManager($courseId): CourseManager {
        return new CourseManagerImpl($courseId);
    }

    public function getFrontManager($frontId): FrontManager {
        return new FrontManagerImpl($frontId);
    }

}
