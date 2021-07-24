<?php

namespace App\Policies;

use App\Models\TakenExam;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TakenExamPolicy
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
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TakenExam  $takenExam
     * @return mixed
     */
    public function view(User $user, TakenExam $takenExam)
    {
        //
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
     * @param  \App\Models\TakenExam  $takenExam
     * @return mixed
     */
    public function update(User $user, TakenExam $takenExam)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TakenExam  $takenExam
     * @return mixed
     */
    public function delete(User $user, TakenExam $takenExam)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TakenExam  $takenExam
     * @return mixed
     */
    public function restore(User $user, TakenExam $takenExam)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TakenExam  $takenExam
     * @return mixed
     */
    public function forceDelete(User $user, TakenExam $takenExam)
    {
        //
    }
}
