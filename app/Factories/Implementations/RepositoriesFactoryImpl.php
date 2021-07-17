<?php

namespace App\Factories\Implementations;

use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Implementations\ExamBlockRepositoryImpl;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Repositories\Implementations\TakenExamRespositoryImpl;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Implementations\FrontRepositoryImpl;
/**
 * Description of RepositoriesFactoryImpl
 *
 * @author piero
 */
class RepositoriesFactoryImpl implements RepositoriesFactory{
    
    public function getExamBlockRepository(): ExamBlockRepository {
       return new ExamBlockRepositoryImpl(); 
    }

    public function getTakenExamRepository(): TakenExamRepository {
        return new TakenExamRespositoryImpl();
    }

    public function getFrontRepository(): FrontRepository {
        return new FrontRepositoryImpl();
    }

}
