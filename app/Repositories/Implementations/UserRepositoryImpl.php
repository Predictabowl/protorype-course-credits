<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Implementations;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use App\Repositories\Interfaces\UserRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * Description of UserRepositoryImpl
 *
 * @author piero
 */
class UserRepositoryImpl implements UserRepository {

    public function delete($id): bool {
        return User::destroy($id);
    }

    public function get($id): ?User {
        return User::with("roles")->find($id);
    }

    public function save(User $user): bool {
        if (isset($user->id)){
            throw new InvalidArgumentException("User ID should be null while saving");
        }
        return $user->save();
    }

    public function addRole($userId, $roleName): bool {
        $role = Role::where("name","$roleName")->first();
        if(!isset($role)){
            return false;
        }

        try{
            RoleUser::FirstOrCreate([
                "role_id" => $role->id,
                "user_id" => $userId
            ]);
        } catch (QueryException $exc){
            Log::error(__CLASS__ . "::" . __METHOD__ . " " . $exc->getMessage());
            return false;
        }
        return true;
    }

    public function removeRole($userId, $roleName): bool {
        $user = User::with("roles")->find($userId);
        if (!isset($user)){
            Log::error(__CLASS__ . "::" . __METHOD__ . ". Could not find User model with id: " . $userId);
            return false;
        }
        $user->roles()->where("name",$roleName)->get()->map(fn($role) =>
                $user->roles()->detach($role));
        return true;
    }

    public function getAll(array $filters, int $numInPage = 50): Paginator{
        return User::with("roles")->filter($filters)->paginate($numInPage);
    }

    public function update(User $user): bool {
        if (!isset($user->id)){
            throw new InvalidArgumentException("User ID missing");
        }
        return $user->update();
    }

    public function getByRole(string $role): Collection {
        return User::whereRelation("roles", "name", $role)->get();
    }

}
