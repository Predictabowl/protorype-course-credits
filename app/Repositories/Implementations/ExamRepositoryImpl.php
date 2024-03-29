<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Repositories\Implementations;

use App\Exceptions\Custom\ExamNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Models\Exam;
use App\Models\Ssd;
use App\Repositories\Interfaces\ExamRepository;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Description of ExamRepositoryImpl
 *
 * @author piero
 */
class ExamRepositoryImpl implements ExamRepository{
    
    public function get($id): ?Exam {
        return Exam::find($id);
    }

    /**
    * The reason why throw an exception when id is set, instead of simply
     * setting to it null, is because we want the calls to this method to be
     * very deliberate, as such we don't want to be called by mistake when
     * someone want to update.
    */
    public function save(Exam $exam): Exam{
        if(isset($exam->id)){
            throw new InvalidArgumentException("New exam id must not be set");
        }
        
        if(isset($exam->ssd_id)){
            $ssd = Ssd::find($exam->ssd_id);
            if(!isset($ssd)){
                throw new SsdNotFoundException("ssd not found with id: ".$exam->ssd_id);
            }
        }
        $exam->save();
        return $exam;
    }

    public function update(Exam $exam): Exam{
        $loaded = Exam::find($exam->id);
        if(!isset($loaded)){
            throw new ExamNotFoundException("Exam not found with id: ".$exam->id);
        }

        if(isset($exam->ssd_id)){
            $ssd = Ssd::find($exam->ssd_id);
            if(!isset($ssd)){
                throw new SsdNotFoundException("ssd not found with id: ".$exam->ssd_id);
            }
        }

        $loaded->name = $exam->name;
        $loaded->ssd_id = $exam->ssd_id;
        $loaded->free_choice = $exam->free_choice;
        $loaded->save();
        return $loaded;
    }

    public function delete(int $id): void {
        Exam::destroy($id);
    }

    public function deleteBatch(Collection $ids): void {
        Exam::destroy($ids);
    }

}