<?php

namespace App\Policies;

use App\User;
use App\User as Member;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function index(User $user)
    {
        return  $user->can('user-list');
    }

    /**
     * Determine whether the user can show the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\User $member
     *
     * @return mixed
     */
    public function show(User $user, Member $member)
    {
        if ($user->hasRole('admin') || ($user->id === $member->id)) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return  $user->can('user-create');
    }

    /**
     * Determine whether the user can update the models.
     *
     * @param \App\User $user
     * @param \App\User $member
     *
     * @return mixed
     */
    public function update(User $user, Member $member)
    {
        if ($user->hasRole('admin') || ($user->id === $member->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function delete(User $user)
    {
        return  $user->can('user-delete');
    }
}
