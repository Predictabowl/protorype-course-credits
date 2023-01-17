<?php

namespace App\Repositories\Implementations;

use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Repositories\Interfaces\ExamBlockRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * Description of ExamBlockRepositoryImpl
 *
 * @author piero
 */
class ExamBlockRepositoryImpl implements ExamBlockRepository{

    public function get(int $id): ?ExamBlock {
        return ExamBlock::with("exams.ssd", "ssds")->find($id);
    }

    public function getFilteredByCourse(int $courseId): Collection{
        $course = Course::with("examBlocks.exams.ssd",
                "examBlocks.ssds")->find($courseId);
        if (!isset($course)){
            throw new ModelNotFoundException("Could not find Course with id: ".$courseId);
        }
        return $course->examBlocks;
    }

    public function save(ExamBlock $examBlock): bool {
        
        if(isset($examBlock->id)){
            throw new InvalidArgumentException("The id of a new ExamBlock must be null");
        }
        
        $course = Course::find($examBlock->course_id);
        if (!isset($course)){
            throw new InvalidArgumentException("Could not find Course with id: ".$examBlock->course_id);
        }
        
        try{
            return $examBlock->save();
        } catch (QueryException $exc){
            Log::error(__CLASS__ . "::" . __METHOD__ . " " . $exc->getMessage());
            return false;
        }
    }

    public function update(ExamBlock $examBlock) {
        $oldEB = ExamBlock::find($examBlock->id);
        if(is_null($oldEB)){
            throw new ExamBlockNotFoundException("Couldn't find ExamBlock with id: ".$examBlock->id);
        }
        $oldEB->max_exams = $examBlock->max_exams;
        $oldEB->cfu = $examBlock->cfu;
        $oldEB->courseYear = $examBlock->courseYear;
        $oldEB->save();
    }

    public function delete(int $id): bool {
        return ExamBlock::destroy($id);
    }

}
