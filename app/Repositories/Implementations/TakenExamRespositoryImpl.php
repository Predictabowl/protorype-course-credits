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
use Illuminate\Support\Collection;

/**
 * Description of TakenExamRespositoryImpl
 *
 * @author piero
 */
class TakenExamRespositoryImpl implements TakenExamRepository{
    
    public function get($id): TakenExamDTO {
        $exam = TakenExam::with("ssd")->find($id);
        return $this->mapTakenExam($exam);
    }

    public function getAll($frontId): Collection {
        return Front::with("takenExams.ssd")->find($frontId)->takenExams->map(
                fn($exam) => $this->mapTakenExam($exam));
    }
    
    public function mapTakenExam(TakenExam $exam): TakenExamDTO {
        return new TakenExamDTO($exam->id, $exam->name, $exam->ssd->code, $exam->cfu);
    }

}
