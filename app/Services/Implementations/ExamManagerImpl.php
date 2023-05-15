<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Domain\NewExamInfo;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Mappers\Interfaces\ExamInfoMapper;
use App\Models\Exam;
use App\Models\Ssd;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\ExamRepository;
use App\Repositories\Interfaces\SSDRepository;
use App\Services\Interfaces\ExamManager;
use Illuminate\Support\Facades\DB;
use function __;

/**
 * Description of ExamManagerImpl
 *
 * @author piero
 */
class ExamManagerImpl implements ExamManager{
    
    private ExamRepository $examRepo;
    private ExamBlockRepository $ebRepo;
    private SSDRepository $ssdRepo;
    private ExamInfoMapper $examMapper;
    
    public function __construct(ExamRepository $examRepo,
            ExamBlockRepository $ebRepo,
            SSDRepository $ssdRepo,
            ExamInfoMapper $examMapper) {
        $this->examRepo = $examRepo;
        $this->ebRepo = $ebRepo;
        $this->ssdRepo = $ssdRepo;
        $this->examMapper = $examMapper;
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
    
    public function deleteExam(int $examId): void{
        DB::transaction(function() use($examId){
            $this->examRepo->delete($examId);
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
    
    private function getSsdOrThrow(string $code): Ssd{
        $ssd = $this->ssdRepo->getSsdFromCode($code);
        if (is_null($ssd)){
            throw new SsdNotFoundException(__("SSD not found").": ".$code);
        }
        return $ssd;
    }
}
