<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaxonomyTheme;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaxonomyThemePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TaxonomyTheme $taxonomyTheme)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TaxonomyTheme $taxonomyTheme)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TaxonomyTheme $taxonomyTheme)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TaxonomyTheme $taxonomyTheme)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TaxonomyTheme $taxonomyTheme)
    {
        //
    }
}