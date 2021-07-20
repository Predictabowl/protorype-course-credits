<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\FrontInfoManagerFactory;
use App\Services\Interfaces\FrontInfoManager;
use App\Services\Implementations\FrontInfoManagerImpl;
use App\Factories\Interfaces\RepositoriesFactory;

/**
 * Description of FrontManagerFactoryImpl
 *
 * @author piero
 */
class FrontInfoManagerFactoryImpl implements FrontInfoManagerFactory{
    
    public function getInstance($frontId): FrontInfoManager{
        return new FrontInfoManagerImpl(app()->make(RepositoriesFactory::class), $frontId);
    }

}
