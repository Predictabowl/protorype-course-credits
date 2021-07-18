<?php

namespace App\Factories\Implementations;

use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Implementations\ExamBlockRepositoryImpl;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Repositories\Implementations\TakenExamRespositoryImpl;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Implementations\FrontRepositoryImpl;
use \App\Repositories\Interfaces\UserRepository;
use App\Repositories\Implementations\UserRepositoryImpl;

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

    public function getUserRepository(): UserRepository {
        return new UserRepositoryImpl();
    }

    public function getCourseRepository(): \App\Factories\Interfaces\CourseRepository {
        throw new Exception("method not implemented yet");
    }

}
