<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Implementations;

use \App\Repositories\Interfaces\UserRepository;
use App\Models\User;

/**
 * Description of UserRepositoryImpl
 *
 * @author piero
 */
class UserRepositoryImpl implements UserRepository {

    public function delete($id): int {
        return User::destroy($id);
    }

    public function get($id): ?User {
        return User::find($id);
    }

    public function save(User $user): ?User {
        if (isset($user->id)){
            throw new \InvalidArgumentException("User ID should be null while saving");
        }
        if (!$user->save()){
            return null;
        }
        return $user;
    }

}
