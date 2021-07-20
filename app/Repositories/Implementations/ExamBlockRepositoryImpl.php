<?php

namespace App\Repositories\Implementations;

use App\Domain\ExamBlockDTO;
use App\Domain\ExamOptionDTO;
use App\Models\ExamBlockOption;
use App\Models\ExamBlock;
use App\Models\Front;
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
        return ExamBlock::with("examBlockOptions.exam.ssd")->find($id);
    }

    public function getFromFront($frontId): Collection {
        $front = Front::with("course.examBlocks.examBlockOptions.exam.ssd")->find($frontId);
        if (!isset($front)){
            throw new ModelNotFoundException("Could not find Front with id: ".$frontId);
        }
        if (!isset($front->course)){
            return collect([]);
        }
        return $front->course->examBlocks;
    }
}
