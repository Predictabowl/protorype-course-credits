<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Exceptions\Custom\CourseNameAlreadyExistsException;
use App\Exceptions\Custom\CourseNotFoundException;
use App\Models\Course;
use App\Repositories\Interfaces\CourseRepository;
use App\Services\Interfaces\CoursesAdminManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class CoursesAdminManagerImpl implements CoursesAdminManager {

    private CourseRepository $courseRepo;

    public function __construct(CourseRepository $courseRepo) {
        $this->courseRepo = $courseRepo;
    }

    public function getAllCourses(?array $filters = []): Collection {
        if(is_null($filters)){
            $filters = [];
        }
        return $this->courseRepo->getAll($filters)
                ->sortBy("name")->values()->collect();
    }

    public function addCourse(Course $course): void{
        $course->id = null;
        DB::transaction(function() use($course){
            $loadedCourse = $this->courseRepo->getFromName($course->name);
            if(!is_null($loadedCourse)){
                throw new CourseNameAlreadyExistsException(
                        "Duplicate course name: ".$course->name);
            }
            $this->courseRepo->save($course);
        });
    }

    public function removeCourse(int $courseId): bool {
        return DB::transaction(function() use($courseId){
            return $this->courseRepo->delete($courseId);
        });
    }

    public function updateCourse(Course $course): void {
        DB::transaction(function() use($course){
            if(is_null($this->courseRepo->get($course->id))){
                throw new CourseNotFoundException("Course not found with id: ".$course->id);
            }
            $this->courseRepo->update($course);
        });
    }

}
