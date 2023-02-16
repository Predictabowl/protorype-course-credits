<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Implementations;

use App\Exceptions\Custom\OperationForbiddenException;
use App\Models\Role;
use App\Repositories\Interfaces\UserRepository;
use App\Services\Interfaces\UserManager;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

/**
 * Description of UserManagerImpl
 *
 * @author piero
 */
class UserManagerImpl implements UserManager{

    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function modRole(int $userId, array $attributes): void {
        DB::transaction(function() use($userId, $attributes){
            if (array_key_exists(Role::ADMIN, $attributes)){
                $this->userRepo->addRole($userId, Role::ADMIN);
            } else {
                $this->checkLastAdminAndThrow($userId);
                $this->userRepo->removeRole($userId, Role::ADMIN);
            }
            if (array_key_exists(Role::SUPERVISOR, $attributes)){
                $this->userRepo->addRole($userId, Role::SUPERVISOR);
            } else {
                $this->userRepo->removeRole($userId, Role::SUPERVISOR);
            }
        });
    }

    public function getAll($filters): Paginator{
        return $this->userRepo->getAll($filters,25);
    }

    public function setName(int $userId, string $name): void {
        DB::transaction(function() use($userId, $name){
            $user = $this->userRepo->get($userId);
            $user->name = $name;
            $this->userRepo->update($user);
        });
    }

    public function deleteUser(int $userId): bool {
        return DB::transaction(function() use($userId){
            $this->checkLastAdminAndThrow($userId);
            return $this->userRepo->delete($userId);
        });
    }
    
    public function isAdminRoleToggable(int $userId): bool {
        return !$this->checkIfLastAdmin($userId);
    }
    
    private function checkLastAdminAndThrow(int $userId): void {
        if ($this->checkIfLastAdmin($userId)){
            throw new OperationForbiddenException(__("Cannot remove the last admin"));
        }
    }
    
    private function checkIfLastAdmin(int $userId): bool{
        $admins = $this->userRepo->getByRole(Role::ADMIN);
        if($admins->count() === 1 && $admins->first()->id === $userId){
            return true;
        }
        return false;
    }


}
