<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\FrontManagerFactory;
use App\Mappers\Interfaces\TakenExamMapper;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Services\Implementations\FrontManagerImpl;
use App\Services\Interfaces\FrontManager;
use function app;

/**
 * Description of ManagersFactoryImpl
 *
 * @author piero
 */
class FrontManagerFactoryImpl implements FrontManagerFactory{
    
    public function getFrontManager($frontId): FrontManager {
        return new FrontManagerImpl($frontId, 
                app()->make(TakenExamMapper::class),
                app()->make(TakenExamRepository::class),
                app()->make(FrontRepository::class),
                app()->make(CourseRepository::class));
    }

}
