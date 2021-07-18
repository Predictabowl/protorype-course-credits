<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Interfaces;

use App\Models\Front;
use App\Services\Interfaces\FrontManager;

/**
 *
 * @author piero
 */
interface UserManager {
    
    /**
     * Must be called to initialize the instance.
     * 
     * @param type $id 1 is successful, 0 otherwise
     */
    public function setUser($id): int;
    
    /**
     * Return a manager for front associated with the user.
     * If the Front exist it will automatically set it up.
     * 
     * @return FrontManager
     */
    public function getFrontManager(): FrontManager;
    
    /**
     * Will return a manager for the $userId specified.
     * If the Front exist it will automatically set it up.
     * Will return null if the userId doesn't cannot be found or if the
     * active user is not an admin.
     * 
     * @param type $userId
     * @return FrontManager|null
     */
    public function getAdminFrontManager($userId): ?FrontManager;
    
}
