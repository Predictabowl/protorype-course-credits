<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepository;
use App\Services\Interfaces\CourseAdminManager;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class CourseAdminManagerImpl implements CourseAdminManager {

    private CourseRepository $courseRepo;

    public function __construct(CourseRepository $courseRepo) {
        $this->courseRepo = $courseRepo;
    }

    public function getCourseFullDepth(int $courseId): ?Course {
        $course = $this->courseRepo->get($courseId,true);
        $course->examBlocks = $course->examBlocks->sortBy("id")->values();
        $course->examBlocks->each(function($item){
                $item->exams = $item->exams->sortBy("name")->values();
            });
        return $course;
    }

    public function getCourse(int $coruseId): ?Course {
        return $this->courseRepo->get($coruseId, false);
    }

}
