<?php

namespace App\Factories\Interfaces;

use App\Repositories\Interfaces\TakenExamRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Interfaces\UserRepository;
use App\Repositories\Interfaces\CourseRepository;

/**
 *
 * @author piero
 */
interface RepositoriesFactory {
    
    public function getTakenExamRepository(): TakenExamRepository;
    
    public function getExamBlockRepository(): ExamBlockRepository;
    
    public function getFrontRepository(): FrontRepository;
    
    public function getUserRepository(): UserRepository;
    
    public function getCourseRepository(): CourseRepository;
}
