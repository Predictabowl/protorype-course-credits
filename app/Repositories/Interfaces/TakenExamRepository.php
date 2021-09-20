<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Interfaces;

use App\Models\TakenExam;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface TakenExamRepository {
    
    public function get($id): ?TakenExam;
    
    public function getFromFront($frontId): Collection;
    
    public function save(TakenExam $exam): bool;
    
    public function delete($id): bool;
    
    public function deleteFromFront($frontId): bool;
    
}
