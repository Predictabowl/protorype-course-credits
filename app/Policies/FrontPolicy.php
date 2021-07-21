<?php

namespace App\Policies;

use App\Models\Front;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class FrontPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return ($user->hasAtLeastOneRole(Role::ADMIN, Role::SUPERVISOR))
               ? Response::allow()
               : Response::deny("Non hai l'autorizzazione per accedere");

    }

    /**
     * Determine whether the user can view the model.
     * .
     * This makes no sense: since the policies are called sequentially 
     * if the view is false also viewAny will be false no matter what.
     * It looks like a bug in the method names mapping (not the first one
     * I find).
     * The next best thing is to use a guard instead...
     * 
     * Since this is too much work and I don't need a list of fronts, I'll just
     * use another controller. Luckily the DB is set to have One to One with 
     * users.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Front  $front
     * @return mixed
     */
    public function view(User $user, Front $front)
    {
        return ($user->id === $front->user_id) ||
               ($user->hasAtLeastOneRole(Role::ADMIN, Role::SUPERVISOR))
               ? Response::allow()
               : Response::deny("Non hai l'autorizzazione per accedere");
                
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Front  $front
     * @return mixed
     */
    public function update(User $user, Front $front)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Front  $front
     * @return mixed
     */
    public function delete(User $user, Front $front)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Front  $front
     * @return mixed
     */
    public function restore(User $user, Front $front)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Front  $front
     * @return mixed
     */
    public function forceDelete(User $user, Front $front)
    {
        //
    }
}
