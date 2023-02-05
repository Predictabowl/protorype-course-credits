<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Factories\Interfaces\UserFrontManagerFactory;
use App\Repositories\Interfaces\FrontRepository;
use App\Services\Implementations\UserFrontManagerImpl;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\UserFrontManager;

/**
 *
 * @author piero
 */
class UserFrontManagerFactoryImpl implements UserFrontManagerFactory{
    
    private FrontRepository $frontRepo;
    private FrontManager $frontManager;
    private StudyPlanBuilderFactory $spbFactory;
    
    public function __construct(FrontRepository $frontRepo,
            FrontManager $frontManager, StudyPlanBuilderFactory $spbFactory) {
        $this->frontRepo = $frontRepo;
        $this->frontManager = $frontManager;
        $this->spbFactory = $spbFactory;
    }

    public function get(int $userId): UserFrontManager {
        return new UserFrontManagerImpl($this->frontRepo,
                $this->frontManager, $this->spbFactory, $userId);
    }

}
