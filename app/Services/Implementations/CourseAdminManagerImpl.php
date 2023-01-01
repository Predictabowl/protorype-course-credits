<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Domain\NewExamBlockInfo;
use App\Domain\NewExamInfo;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\ExamRepository;
use App\Services\Interfaces\CourseAdminManager;
use App\Services\Interfaces\SSDRepository;
use Illuminate\Support\Collection;
use TheSeer\Tokenizer\Exception;
use function PHPUnit\Framework\isNull;

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

    public function getAll(): Collection {
        return $this->courseRepo->getAll()->sortBy("name")->values()->collect();
    }

    public function addExamOfChoice($examBlockId): bool {
        throw new Exception("Method not yet implemented");
    }

    public function getAllCourses(): Collection {
        throw new Exception("Method not yet implemented");
    }

    public function getCourseBlocks($courseId): Collection {
        throw new Exception("Method not yet implemented");
    }

    public function saveExam(NewExamInfo $exam, $examBlockId): ?Exam {
        $ssd = $this->ssdRepo->getSsdFromCode($exam->getSsd());
        if (isNull($ssd)){
            return null;
        }
        $exam = new Exam([
            "name" => $exam->getName()
        ]);
//        $this->examRepo->save($exam)
    }

    public function saveExamBlock(NewExamBlockInfo $examBlock, $courseId): ?ExamBlock {
        throw new Exception("Method not yet implemented");
    }

    public function updateExam(Exam $exam): bool {
        throw new Exception("Method not yet implemented");
    }

    public function updateExamBlock(ExamBlock $examBlock): bool {
        throw new Exception("Method not yet implemented");
    }

}
