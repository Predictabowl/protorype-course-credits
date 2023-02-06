<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Exceptions\Custom\CourseNameAlreadyExistsException;
use App\Models\Course;
use App\Repositories\Interfaces\CourseRepository;
use App\Services\Interfaces\CourseManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use function __;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class CourseManagerImpl implements CourseManager {

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
    
    public function getAllCourses(?array $filters = []): Collection {
        if(is_null($filters)){
            $filters = [];
        }
        return $this->courseRepo->getAll($filters)
                ->sortBy("name")->values()->collect();
    }

    public function addCourse(Course $course): Course{
        unset($course["id"]);
        return DB::transaction(function() use($course){
            $loadedCourse = $this->courseRepo->getFromName($course->name);
            if(!is_null($loadedCourse)){
                throw new CourseNameAlreadyExistsException(
                        __("Course Name already present").": ".$course->name);
            }
            return $this->courseRepo->save($course);
        });
    }

    public function removeCourse(int $courseId): bool {
        return DB::transaction(function() use($courseId){
            return $this->courseRepo->delete($courseId);
        });
    }

    public function updateCourse(Course $course): Course{
        return DB::transaction(function() use($course){
            if(is_null($course->id)){
                throw new InvalidArgumentException("Course Id is not properly set");
            }
            
            $nameCourse = $this->courseRepo->getFromName($course->name);
            if(!is_null($nameCourse) && $nameCourse->id != $course->id){
                throw new CourseNameAlreadyExistsException(
                    __("Course Name already present").": ".$course->name);
            }
            return $this->courseRepo->update($course);
        });
    }    

}
