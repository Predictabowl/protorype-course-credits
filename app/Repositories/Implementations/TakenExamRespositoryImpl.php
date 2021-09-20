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
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
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
        if (isset($exam->id)){
            throw new \InvalidArgumentException("The id of a new TakenExam must be null");
        }
        
        try {
            return $exam->save();
        } catch(QueryException $exc){
            Log::error(__CLASS__ . "::" . __METHOD__ . " " . $exc->getMessage());
            return false;
        }
    }
    
    public function delete($id): bool {
        return TakenExam::destroy($id);
    }

    public function deleteFromFront($frontId): bool {
        $front = Front::with("takenExams")->find($frontId);
        if (!isset($front)){
            return false;
        }
        $front->takenExams->each(function (TakenExam $exam){
            $exam->delete();
        });
        return true;
    }

}
