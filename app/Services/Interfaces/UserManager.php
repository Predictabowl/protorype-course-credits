<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Interfaces;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface UserManager {
    
    public function getAll($filters): Collection;
    
    public function modRole(User $user, array $attributes);
    
}
