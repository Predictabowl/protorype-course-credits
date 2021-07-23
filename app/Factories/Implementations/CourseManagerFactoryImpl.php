<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\CourseManagerFactory;
use App\Services\Interfaces\CourseManager;
use App\Services\Implementations\CourseManagerImpl;

/**
 * Description of ManagersFactoryImpl
 *
 * @author piero
 */
class CourseManagerFactoryImpl implements CourseManagerFactory{
    
    public function getCourseManager($courseId): CourseManager {
        return new CourseManagerImpl($courseId);
    }
}
