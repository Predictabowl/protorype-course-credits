<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Domain\NewExamBlockInfo;
use App\Exceptions\Custom\CourseNotFoundException;
use App\Mappers\Interfaces\ExamBlockInfoMapper;
use App\Models\ExamBlock;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\SSDRepository;
use App\Services\Interfaces\ExamBlockManager;
use Illuminate\Support\Facades\DB;

/**
 * Description of ExamBlockManagerImpl
 *
 * @author piero
 */
class ExamBlockManagerImpl implements ExamBlockManager {
    
    private CourseRepository $courseRepo;
    private ExamBlockRepository $ebRepo;
    private SSDRepository $ssdRepo;
    private ExamBlockInfoMapper $ebMapper;

    public function __construct(CourseRepository $courseRepo,
            ExamBlockRepository $ebRepo,
            SSDRepository $ssdRepo,
            ExamBlockInfoMapper $ebMapper) {
        $this->courseRepo = $courseRepo;
        $this->ebRepo = $ebRepo;
        $this->ssdRepo = $ssdRepo;
        $this->ebMapper = $ebMapper;
    }

    public function addSsd(int $examBlockId, string $ssd): void {
        throw new \Exception("Method not yet implemented");
    }

    public function eagerLoadExamBlock(int $examBlockId): ExamBlock {
        throw new \Exception("Method not yet implemented");
    }

    public function removeSsd(int $examBlockId, int $ssdId): void {
        throw new \Exception("Method not yet implemented");
    }

    public function saveExamBlock(NewExamBlockInfo $examBlock, int $courseId): ExamBlock {
        return DB::transaction(function () use ($examBlock, $courseId) {
                    $course = $this->courseRepo->get($courseId);
                    if (is_null($course)) {
                        throw new CourseNotFoundException("Course not found with id: " . $courseId);
                    }
                    $examBlock = $this->ebMapper->map($examBlock, $courseId);
                    return $this->ebRepo->save($examBlock);
                });
    }

    public function updateExamBlock(NewExamBlockInfo $examBlockInfo, int $examBlockId): ExamBlock {
        return DB::transaction(function () use ($examBlockInfo, $examBlockId) {
                    $newExamBlock = $this->ebMapper->map($examBlockInfo, null);
                    $newExamBlock->id = $examBlockId;
                    return $this->ebRepo->update($newExamBlock);
                });
    }

    public function deleteExamBlock(int $examBlockId): void {
        DB::transaction(function () use ($examBlockId) {
            $this->ebRepo->delete($examBlockId);
        });
    }

}
