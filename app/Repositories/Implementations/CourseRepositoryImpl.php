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
use App\Repositories\Interfaces\ExamBlockRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * Description of CourseRepositoryImpl
 *
 * @author piero
 */
class CourseRepositoryImpl implements CourseRepository {
    
    private ExamBlockRepository $ebRepo;
    
    public function __construct(ExamBlockRepository $ebRepo) {
        $this->ebRepo = $ebRepo;
    }

    public function delete(int $id): bool {
        $course = Course::with("examBlocks")->find($id);
        if(is_null($course)){
            return false;
        }
        $course->examBlocks->each(function($examBlock){
            $this->ebRepo->delete($examBlock->id);
        });
        return Course::destroy($id);
    }

    public function get(int $id, bool $fullDepth = false): ?Course {
        if ($fullDepth){
            return Course::with("examBlocks.examBlockOptions.exam")->find($id);
        }
        return Course::find($id);
    }

    public function save(Course $course): bool {
        if (isset($course->id)){
            throw new InvalidArgumentException("The id of a new Course must be empty");
        }

        try{
            return $course->save();
        } catch (QueryException $exc){
            Log::error(__CLASS__ . "::" . __METHOD__ . " " . $exc->getMessage());
            return false;
        }
    }

    public function getAll(array $filters = []): Collection {
        return Course::filter($filters)->get();
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

    public function getFromName(string $name): ?Course {
        return Course::where("name","=",$name)->get()->first();
    }

}
