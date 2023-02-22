<?php

namespace App\Policies;

use App\Models\EcPoi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EcPoiPolicy
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
        if ($user->isAdmin() == true || $user->isEditor() == true) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EcPoi  $ecPoi
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, EcPoi $ecPoi)
    {
        if ($user->isAdmin() == true) {
            return true;
        } elseif ($user->isEditor() == true  && $user->id === $ecPoi->user_id) {
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
        return $user->isEditor();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EcPoi  $ecPoi
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, EcPoi $ecPoi)
    {
        if ($user->isAdmin() == true) {
            return true;
        } elseif ($user->isEditor() == true && $user->id === $ecPoi->user_id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EcPoi  $ecPoi
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, EcPoi $ecPoi)
    {
        if ($user->isAdmin() == true) {
            return true;
        } elseif ($user->isEditor() == true && $user->id === $ecPoi->user_id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EcPoi  $ecPoi
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, EcPoi $ecPoi)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EcPoi  $ecPoi
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, EcPoi $ecPoi)
    {
        //
    }
}
