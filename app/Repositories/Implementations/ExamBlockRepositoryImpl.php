<?php

namespace App\Repositories\Implementations;

use App\Models\ExamBlock;
use App\Models\Course;
use App\Repositories\Interfaces\ExamBlockRepository;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

/**
 * Description of ExamBlockRepositoryImpl
 *
 * @author piero
 */
class ExamBlockRepositoryImpl implements ExamBlockRepository{
    
    public function get($id): ?ExamBlock {
        return ExamBlock::with("examBlockOptions.exam.ssd",
                "examBlockOptions.ssds")->find($id);
    }

    public function getFilteredByCourse($courseId): Collection {
        $course = Course::with("examBlocks.examBlockOptions.exam.ssd",
                "examBlocks.examBlockOptions.ssds")->find($courseId);
        if (!isset($course)){
            throw new ModelNotFoundException("Could not find Course with id: ".$courseId);
        }
        return $course->examBlocks;
    }

    public function save(ExamBlock $examBlock): bool {
        
        if(isset($examBlock->id)){
            throw new \InvalidArgumentException("The id of a new ExamBlock must be null");
        }
        
        $course = Course::find($examBlock->course_id);
        if (!isset($course)){
            throw new \InvalidArgumentException("Could not find Course with id: ".$examBlock->course_id);
        }
        
        try{
            return $examBlock->save();
        } catch (QueryException $exc){
            Log::error(__CLASS__ . "::" . __METHOD__ . " " . $exc->getMessage());
            return false;
        }
    }

}
