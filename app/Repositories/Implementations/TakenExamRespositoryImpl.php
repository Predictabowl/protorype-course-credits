<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\TakenExamRepository;
use App\Models\TakenExam;
use App\Models\Front;
use Illuminate\Support\Collection;

/**
 * Description of TakenExamRespositoryImpl
 *
 * @author piero
 */
class TakenExamRespositoryImpl implements TakenExamRepository{
    
    public function get($id): ?TakenExam {
        return TakenExam::with("ssd")->find($id);
    }

    public function getFromFront($frontId): Collection {
        $front = Front::with("takenExams.ssd")->find($frontId);
        if (!isset($front)){
            return collect([]);
        }
        return $front->takenExams;
    }
    
    public function save(TakenExam $exam): bool {
        return $exam->save();
    }
    
    public function delete($id): bool {
        return TakenExam::destroy($id);
    }
    
}
