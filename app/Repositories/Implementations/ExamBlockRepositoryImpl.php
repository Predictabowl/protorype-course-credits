<?php

namespace App\Repositories\Implementations;

use App\Exceptions\Custom\CourseNotFoundException;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Repositories\Interfaces\ExamBlockRepository;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Description of ExamBlockRepositoryImpl
 *
 * @author piero
 */
class ExamBlockRepositoryImpl implements ExamBlockRepository{

    public function getWithFullDepth(int $id): ?ExamBlock {
        return ExamBlock::with("exams.ssd", "ssds")->find($id);
    }
    
    public function get(int $id): ?ExamBlock {
        return ExamBlock::find($id);
    }
    
    public function getWithSsds(int $id): ?ExamBlock {
        return ExamBlock::with("ssds")->find($id);
    }

    public function save(ExamBlock $examBlock): ExamBlock{
        
        if(isset($examBlock->id)){
            throw new InvalidArgumentException("The id of a new ExamBlock must be null");
        }
        
        $course = Course::find($examBlock->course_id);
        if (!isset($course)){
            throw new CourseNotFoundException("Could not find Course with id: ".$examBlock->course_id);
        }
        
        $examBlock->save();
        return $examBlock;
    }

    public function update(ExamBlock $examBlock): ExamBlock {
        $oldEB = ExamBlock::find($examBlock->id);
        if(is_null($oldEB)){
            throw new ExamBlockNotFoundException("Couldn't find ExamBlock with id: ".$examBlock->id);
        }
        $oldEB->max_exams = $examBlock->max_exams;
        $oldEB->cfu = $examBlock->cfu;
        $oldEB->courseYear = $examBlock->courseYear;
        $oldEB->save();
        return $oldEB;
    }

    public function delete(int $id): bool {
        return ExamBlock::destroy($id);
    }

    public function attachSsd(int $examBlockId, int $ssdId): void {
        DB::table("exam_block_ssd")->insert([
            "ssd_id" => $ssdId,
            "exam_block_id" => $examBlockId
        ]);
    }

    public function detachSsd(int $examBlockId, int $ssdId): void {
        DB::table("exam_block_ssd")
                ->where("ssd_id", "=", $ssdId)
                ->where("exam_block_id", "=", $examBlockId)
                ->delete();
    }

}
