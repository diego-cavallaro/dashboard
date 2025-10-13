<?php

namespace App\Policies;

use App\Models\Doc;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class DocPolicy
{
    public function before($user)
    {
        // si hasRole "tiene el rol" devuelve verdadero y puede hacer siempre cualquier accion
        // si no continua con el resto, no devolver false si no cumple porque no se ejecutarian el resto de funcioness
        if( $user->hasRole('siteAdminRole'))
        {
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * compara si es el creador del doc o tiene permiso para ... 
     */
    public function view(User $user, Doc $doc): bool
    {
        return $user->id === $doc->user_id
            || $user->hasPermissionTo('docView');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('docCreate');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Doc $doc): bool
    {
        return $user->id === $doc->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Doc $doc): bool
    {
        return $user->id === $doc->user_id;
            //|| $user->hasPermissionTo('docDelete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Doc $doc): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Doc $doc): bool
    {
        //
    }
}
