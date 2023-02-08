<?php

namespace App\Policies;

use App\Models\EcTrack;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EcTrackPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->is_admin == true || $user->is_editor == true) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EcTrack  $ecTrack
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, EcTrack $ecTrack)
    {
        if ($user->is_admin == true) {
            return true;
        } elseif ($user->is_editor == true  && $user->id === $ecTrack->user_id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->is_editor;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EcTrack  $ecTrack
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, EcTrack $ecTrack)
    {
        if ($user->is_admin == true) {
            return true;
        } elseif ($user->is_editor == true && $user->id === $ecTrack->user_id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EcTrack  $ecTrack
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, EcTrack $ecTrack)
    {
        if ($user->is_admin == true) {
            return true;
        } elseif ($user->is_editor == true && $user->id === $ecTrack->user_id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EcTrack  $ecTrack
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, EcTrack $ecTrack)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EcTrack  $ecTrack
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, EcTrack $ecTrack)
    {
        //
    }
}
