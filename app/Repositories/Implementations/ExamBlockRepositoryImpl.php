<?php

namespace App\Repositories\Implementations;

use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Exceptions\Custom\ExamNotFoundException;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\ExamBlockOption;
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
        $examBlock->save();
    }

    public function attachExam($examBlockId, $examId): bool {
        $examBlock = ExamBlock::find($examBlockId);
        if(is_null($examBlock)){
            throw new ExamBlockNotFoundException("Couldn't find ExamBlock with id: ".$examBlockId);
        }
        $exam = Exam::find($examId);
        if(is_null($exam)){
            throw new ExamNotFoundException("Couldn't find Exam with id: ".$examBlockId);
        }
        $oldRelation = ExamBlockOption::where("exam_id","=",$exam->id)
                ->where("exam_block_id","=",$examBlock->id)->get();
        if($oldRelation->isEmpty()){
            ExamBlockOption::create([
                "exam_id" => $exam->id,
                "exam_block_id"=> $examBlock->id
            ]);
            return true;
        }
        return false;
    }

    public function detachExam($examBlockId, $examId): bool {
        return false;
    }

}
