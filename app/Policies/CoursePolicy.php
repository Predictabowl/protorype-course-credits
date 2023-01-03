<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Symfony\Component\HttpFoundation\Response as Response2;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->adminOnly($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Course  $course
     * @return Response|bool
     */
    public function view(User $user, Course $course)
    {
        return ($user->hasAtLeastOneRole(Role::ADMIN, Role::SUPERVISOR));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $this->adminOnly($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Course  $course
     * @return Response|bool
     */
    public function update(User $user, Course $course)
    {
        return $this->adminOnly($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Course  $course
     * @return Response|bool
     */
    public function delete(User $user)
    {
        return $this->adminOnly($user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Course  $course
     * @return Response|bool
     */
    public function restore(User $user, Course $course)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  Course  $course
     * @return Response|bool
     */
    public function forceDelete(User $user, Course $course)
    {
        //
    }
    
    private function adminOnly(User $user){
        return ($user->isAdmin()
            ? Response::allow()
            : Response::deny("Questa azione richiede un'autorizzazione da amministratore")
        );
    }
}
