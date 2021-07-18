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

    /**
     * Create a new Front if not present.
     * If there's already the same user_id present then the front can't
     * be saved.
     * 
     * @param type $courseId
     * @param type $userId
     * @return Front|null the saved Front, null if can't be saved
     */
    public function save(Front $front): ?Front;
    
    /**
     * Update the course of an existing front.
     * 
     * Return the updated front
     * @param type $id
     * @param type $courseId
     * @return Front|null the updated front.
     */
    public function updateCourse($id, $courseId): ?Front;

    public function delete($id): int;
}
