<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;

/**
 *
 * @author piero
 */
interface UserManager {
    
    public function getAll($filters): Paginator;
    
    public function modRole(User $user, array $attributes);
    
}
