<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Domain\NewExamBlockInfo;
use App\Domain\NewExamInfo;
use App\Exceptions\Custom\CourseNotFoundException;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Mappers\Interfaces\ExamBlockInfoMapper;
use App\Mappers\Interfaces\ExamInfoMapper;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\Ssd;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\ExamRepository;
use App\Repositories\Interfaces\SSDRepository;
use App\Services\Interfaces\CourseAdminManager;
use Illuminate\Support\Facades\DB;
use function __;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class CourseAdminManagerImpl implements CourseAdminManager {

    private CourseRepository $courseRepo;
    private ExamBlockRepository $ebRepo;
    private ExamRepository $examRepo;
    private SSDRepository $ssdRepo;
    private ExamBlockInfoMapper $ebMapper;
    private ExamInfoMapper $examMapper;

    public function __construct(CourseRepository $courseRepo,
            ExamBlockRepository $ebRepo,
            ExamRepository $examRepo,
            SSDRepository $ssdRepo,
            ExamBlockInfoMapper $ebMapper,
            ExamInfoMapper $examMapper) {
        $this->courseRepo = $courseRepo;
        $this->ebRepo = $ebRepo;
        $this->examRepo = $examRepo;
        $this->ssdRepo = $ssdRepo;
        $this->ebMapper = $ebMapper;
        $this->examMapper = $examMapper;
    }

    public function getCourseFullData(int $courseId): ?Course {
        return $this->courseRepo->get($courseId,true);
    }

    public function saveExam(NewExamInfo $exam, int $examBlockId): Exam{
        return DB::transaction(function() use($exam, $examBlockId){
            if($exam->isFreeChoice()){
                $ssdId = null;
            } else {
                $ssdId = $this->getSsdOrThrow($exam->getSsd())->id;
            }
            $examBlock = $this->ebRepo->get($examBlockId);
            if(is_null($examBlock)){
                throw new ExamBlockNotFoundException(
                        "Exam Block not found with id: ".$examBlockId);
            }
            $modelExam = $this->examMapper->map($exam, $examBlockId, $ssdId);
            return $this->examRepo->save($modelExam);
        });
    }

    public function saveExamBlock(NewExamBlockInfo $examBlock, int $courseId): ExamBlock {
        return DB::transaction(function() use($examBlock, $courseId){
            $course = $this->courseRepo->get($courseId);
            if (is_null($course)){
                throw new CourseNotFoundException("Course not found with id: ".$courseId);
            }
            $examBlock = $this->ebMapper->map($examBlock, $courseId);
            return $this->ebRepo->save($examBlock);
        });
    }
    
    public function deleteExam(int $examId): void{
        DB::transaction(function() use($examId){
            $this->examRepo->delete($examId);
        });
    }

    public function deleteExamBlock(int $examBlockId): void{
        DB::transaction(function() use($examBlockId){
            $this->ebRepo->delete($examBlockId);
        });
    }

    public function updateExam(NewExamInfo $examInfo, int $examId): Exam{
        return DB::transaction(function() use($examInfo, $examId){
            $ssdId = null;
            if(!is_null($examInfo->getSsd())){
                $ssdId = $this->getSsdOrThrow($examInfo->getSsd())->id;
            }
            $newExam = $this->examMapper->map($examInfo, null, $ssdId);
            $newExam->id = $examId;
            return $this->examRepo->update($newExam);
        });
        
    }

    public function updateExamBlock(NewExamBlockInfo $examBlockInfo, int $examBlockId): void {
        DB::transaction(function() use($examBlockInfo, $examBlockId){
            $newExamBlock = $this->ebMapper->map($examBlockInfo, null);
            $newExamBlock->id = $examBlockId;
            $this->ebRepo->update($newExamBlock);
        });
    }

    private function getSsdOrThrow(string $code): Ssd{
        $ssd = $this->ssdRepo->getSsdFromCode($code);
        if (is_null($ssd)){
            throw new SsdNotFoundException(__("SSD not found").": ".$code);
        }
        return $ssd;
    }
}
