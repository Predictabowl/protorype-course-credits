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
use App\Models\Ssd;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\ExamRepository;
use App\Repositories\Interfaces\SSDRepository;
use App\Services\Interfaces\CourseAdminManager;
use Illuminate\Support\Facades\DB;
use TheSeer\Tokenizer\Exception;

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

    public function saveExam(NewExamInfo $exam, int $examBlockId): void {
        DB::transaction(function() use($exam, $examBlockId){
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
            $this->examRepo->save($modelExam);
        });
    }

    public function saveExamBlock(NewExamBlockInfo $examBlock, int $courseId): void {
        DB::transaction(function() use($examBlock, $courseId){
            $course = $this->courseRepo->get($courseId);
            if (is_null($course)){
                throw new CourseNotFoundException("Course not found with id: ".$courseId);
            }
            $examBlock = $this->ebMapper->map($examBlock, $courseId);
            $this->ebRepo->save($examBlock);
        });
    }
    
    public function deleteExam(int $examId): bool {
        throw new Exception("Method not yet implemented");
    }

    public function deleteExamBlock(int $examBlockId): bool {
        throw new Exception("Method not yet implemented");
    }

    public function updateExam(NewExamInfo $exam, int $examId): bool {
        throw new Exception("Method not yet implemented");
    }

    public function updateExamBlock(NewExamBlockInfo $examBlock, int $examBlockId): bool {
        throw new Exception("Method not yet implemented");
    }

    private function getSsdOrThrow(string $code): Ssd{
        $ssd = $this->ssdRepo->getSsdFromCode($code);
        if (is_null($ssd)){
            throw new SsdNotFoundException("Ssd not found with code: ".$code);
        }
        return $ssd;
    }
}
