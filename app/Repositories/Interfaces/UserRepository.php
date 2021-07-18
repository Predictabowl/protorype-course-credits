<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Interfaces;

use App\Models\User;

/**
 *
 * @author piero
 */
interface UserRepository {
    
    public function get($id): ?User;
    
    public function save(User $user): ?User;
    
    public function delete($id): int;
}
