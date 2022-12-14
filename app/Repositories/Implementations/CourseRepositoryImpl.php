<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Implementations;

use App\Exceptions\Custom\CourseNotFoundException;
use App\Models\Course;
use App\Repositories\Interfaces\CourseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * Description of CourseRepositoryImpl
 *
 * @author piero
 */
class CourseRepositoryImpl implements CourseRepository {

    public function delete($id): bool {
        return Course::destroy($id);
    }

    public function get($id): ?Course {
        return Course::find($id);        
    }

    public function save(Course $course): bool {
        if (isset($course->id)){
            throw new InvalidArgumentException("The id of a new Course must be null");
        }
        
        try{
            return $course->save();
        } catch (QueryException $exc){
            Log::error(__CLASS__ . "::" . __METHOD__ . " " . $exc->getMessage());
            return false;
        }
    }

    public function getAll() {
        return Course::all();
    }

    public function update(Course $course): bool {
        $oldCourse = Course::find($course->id);
        if(!isset($oldCourse)){
            throw new CourseNotFoundException("Course not found with id: ".$course->id);
        }
        try{
            return $course->save();
        } catch (QueryException $exc){
            Log::error(__CLASS__ . "::" . __METHOD__ . " " . $exc->getMessage());
            return false;
        }
    }

}
