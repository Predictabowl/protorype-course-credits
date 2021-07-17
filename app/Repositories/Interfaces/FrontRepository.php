<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Interfaces;

use App\Models\Front;

/**
 * Description of FrontRepository
 *
 * @author piero
 */
interface FrontRepository {
    
    public function get($id): ?Front;
    
    public function getFromUser($id): ?Front;

    public function save($courseId, $userId): ?Front;
    
    public function updateCourse($id, $courseId): ?Front;

    public function delete($id): int;
}
