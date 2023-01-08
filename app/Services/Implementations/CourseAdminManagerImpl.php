<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Domain\NewExamBlockInfo;
use App\Domain\NewExamInfo;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
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

    public function __construct(CourseRepository $courseRepo,
            ExamBlockRepository $ebRepo,
            ExamRepository $examRepo,
            SSDRepository $ssdRepo) {
        $this->courseRepo = $courseRepo;
        $this->ebRepo = $ebRepo;
        $this->examRepo = $examRepo;
        $this->ssdRepo = $ssdRepo;
    }

    public function addExamOfChoice($examBlockId): bool {
        throw new Exception("Method not yet implemented");
    }

    public function getCourseFullData($courseId): ?Course {
        return $this->courseRepo->get($courseId,true);
    }

    public function saveExam(NewExamInfo $exam, $examBlockId): Exam {
        return DB::transaction(function() use($exam, $examBlockId){
            if(!is_null($exam->getSsd())){
                $ssd = $this->ssdRepo->getSsdFromCode($exam->getSsd());
                if (is_null($ssd)){
                    throw new SsdNotFoundException(
                            "Ssd not found with code: ".$exam->getSsd());
                }
                $ssdId = $ssd->id;
            } else {
                $ssdId = null;
            }
            $examBlock = $this->ebRepo->get($examBlockId);
            if(is_null($examBlock)){
                throw new ExamBlockNotFoundException(
                        "Exam Block not found with id: ".$examBlockId);
            }
            $modelExam = new Exam([
                "name" => $exam->getName(),
                "ssd_id" => $ssdId,
                "free_choice" => $exam->isFreeChoice()]);
            $savedExam = $this->examRepo->save($modelExam);
            return $savedExam;
        });
    }

    public function saveExamBlock(NewExamBlockInfo $examBlock, $courseId): ExamBlock {
        throw new Exception("Method not yet implemented");
    }

    public function updateExam(Exam $exam): bool {
        throw new Exception("Method not yet implemented");
    }

    public function updateExamBlock(ExamBlock $examBlock): bool {
        throw new Exception("Method not yet implemented");
    }

}
