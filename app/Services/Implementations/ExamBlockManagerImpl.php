<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Domain\NewExamBlockInfo;
use App\Domain\SsdCode;
use App\Exceptions\Custom\CourseNotFoundException;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Mappers\Interfaces\ExamBlockInfoMapper;
use App\Models\ExamBlock;
use App\Models\Ssd;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\SSDRepository;
use App\Services\Interfaces\ExamBlockManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use function __;

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

    public function addSsd(int $examBlockId, SsdCode $ssdCode): void {
        DB::transaction(function() use($examBlockId, $ssdCode){
            $ssd = $this->getSsdOrThrow($ssdCode);
            if (!$ssd->examBlocks->contains("id", $examBlockId)) {
                $this->ebRepo->attachSsd($examBlockId, $ssd->id);
            }
        });
    }

    public function getExamBlockWithSsds(int $examBlockId): ExamBlock {
        $examBlock = $this->ebRepo->getWithSsds($examBlockId);
        if (!isset($examBlock)){
            throw new ExamBlockNotFoundException(
                    __("Exam Block not found")." id: ".$examBlockId);
        }
        return $examBlock;
    }

    public function removeSsd(int $examBlockId, int $ssdId): void {
        DB::transaction(function() use($examBlockId, $ssdId) {
            $this->ebRepo->detachSsd($examBlockId, $ssdId);
        });
    }

    public function saveExamBlock(NewExamBlockInfo $examBlock, int $courseId): ExamBlock {
        return DB::transaction(
            function () use ($examBlock, $courseId) {
                $course = $this->courseRepo->get($courseId);
                if (is_null($course)) {
                    throw new CourseNotFoundException("Course not found with id: " . $courseId);
                }
                $examBlock = $this->ebMapper->map($examBlock, $courseId);
                return $this->ebRepo->save($examBlock);
            });
    }

    public function updateExamBlock(NewExamBlockInfo $examBlockInfo, int $examBlockId): ExamBlock {
        return DB::transaction(
            function () use ($examBlockInfo, $examBlockId) {
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
    
    private function getSsdOrThrow(SsdCode $code): Ssd{
        $ssd = $this->ssdRepo->getSsdFromCodeWithExamBlocks($code->getCode());
        if (is_null($ssd)){
            throw new SsdNotFoundException(__("SSD not found").": ".$code->getCode());
        }
        return $ssd;
    }

    public function getAllSsds(): Collection {
        return $this->ssdRepo->getAll();
    }

}
