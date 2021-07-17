<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\TakenExamRepository;
use App\Domain\TakenExamDTO;
use App\Models\TakenExam;
use App\Models\Front;
use App\Models\Ssd;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Description of TakenExamRespositoryImpl
 *
 * @author piero
 */
class TakenExamRespositoryImpl implements TakenExamRepository{
    
    public function get($id): ?TakenExamDTO {
        $exam = TakenExam::with("ssd")->find($id);
        return $this->mapTakenExam($exam);
    }

    public function getFromFront($frontId): Collection {
        $front = Front::with("takenExams.ssd")->find($frontId);
        if (!isset($front)){
            throw new ModelNotFoundException("Could not find Front with id: ".$frontId);
        }
        return $front->takenExams->map(
                fn($exam) => $this->mapTakenExam($exam));
    }
    
    public function save(TakenExamDTO $exam, int $frontId) {
        TakenExam::create([
           "name" => $exam->getExamName(),
            "cfu" => $exam->getCfu(),
            "ssd_id" => Ssd::where("code",$exam->getSsd())->first()->id,
            "front_id" => $frontId
        ]);
    }
    
    public function delete($id): int {
        return TakenExam::destroy($id);
    }
    
    
    public function mapTakenExam(?TakenExam $exam): ?TakenExamDTO {
        if (!isset($exam)){
            return null;
        }
        return new TakenExamDTO($exam->id, $exam->name, $exam->ssd->code, $exam->cfu);
    }
}
