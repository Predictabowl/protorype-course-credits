<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\CourseRepository;
use App\Models\Course;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

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
        if (isset($front->id)){
            throw new \InvalidArgumentException("The id of a new Course must be null");
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

}
