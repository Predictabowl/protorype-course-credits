<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Repositories\Interfaces\CourseRepository;
use App\Services\Interfaces\CourseAdminManager;
use Illuminate\Support\Collection;
use function app;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class CourseAdminManagerImpl implements CourseAdminManager{
    
    private CourseRepository $courseRepo;
    
    public function __construct() {
        $this->courseRepo = app()->make(CourseRepository::class);
    }

    
    public function getAll(): Collection {
        return collect($this->courseRepo->getAll());
    }
    
}
