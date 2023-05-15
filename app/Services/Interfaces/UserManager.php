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
    
    public function modRole(int $userId, array $attributes): void;
    
    public function setName(int $userId, string $name): void;
    
    public function deleteUser(int $userId): bool;
    
    public function isAdminRoleToggable(int $userId): bool;
}
