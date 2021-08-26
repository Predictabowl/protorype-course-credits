<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;

/**
 *
 * @author piero
 */
interface UserRepository {
    
    public function get($id): ?User;
    
    public function getAll(array $filters, int $numInPage): Paginator;
    
    public function save(User $user): bool;
    
    public function update(User $user): bool;
    
    public function delete($id): bool;
    
    public function addRole($userId, $roleName): bool;
    
    public function removeRole($userId, $roleName): bool;
    
}
