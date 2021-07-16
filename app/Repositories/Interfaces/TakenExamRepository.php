<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Interfaces;

use App\Domain\TakenExamDTO;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface TakenExamRepository {
    
    public function get($id): TakenExamDTO;
    
    public function getFromFront($frontId): Collection;
    
    public function save(TakenExamDTO $exam, int $frontId);
    
    public function delete($id);
}
