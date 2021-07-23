<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\FrontManagerFactory;
use App\Services\Interfaces\FrontManager;
use App\Services\Implementations\FrontManagerImpl;

/**
 * Description of ManagersFactoryImpl
 *
 * @author piero
 */
class FrontManagerFactoryImpl implements FrontManagerFactory{
    
    public function getFrontManager($frontId): FrontManager {
        return new FrontManagerImpl($frontId);
    }

}
