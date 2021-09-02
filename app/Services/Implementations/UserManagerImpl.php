<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Implementations;

use App\Models\Role;
use App\Repositories\Interfaces\UserRepository;
use App\Services\Interfaces\UserManager;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Description of UserManagerImpl
 *
 * @author piero
 */
class UserManagerImpl implements UserManager{
    
    
    public function modRole($userId, array $attributes) {
        $userRepo = $this->getUserRepository();

        if (array_key_exists(Role::ADMIN, $attributes)){            
            $userRepo->addRole($userId, Role::ADMIN);
        } else {
            $userRepo->removeRole($userId, Role::ADMIN);
        }
        if (array_key_exists(Role::SUPERVISOR, $attributes)){
            $userRepo->addRole($userId, Role::SUPERVISOR);
        } else {
            $userRepo->removeRole($userId, Role::SUPERVISOR);
        }
    }

    public function getAll($filters): Paginator{
        return $this->getUserRepository()->getAll($filters,25);
    }
    
    public function setName($userId, string $name) {
        $repo = $this->getUserRepository();
        $user = $repo->get($userId);
        $user->name = $name;
        $repo->update($user);
    }
    
    public function deleteUser($userId): bool {
        return $this->getUserRepository()->delete($userId);
    }
    
    private function getUserRepository(): UserRepository{
        return app()->make(UserRepository::class);
    }

}
