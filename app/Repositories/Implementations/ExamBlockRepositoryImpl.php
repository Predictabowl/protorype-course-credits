<?php

namespace App\Repositories\Implementations;

use App\Models\ExamBlock;
use App\Models\Course;
use App\Repositories\Interfaces\ExamBlockRepository;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

}
