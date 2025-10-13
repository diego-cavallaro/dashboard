<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
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

    public function view(User $authUser): bool
    {
        return $authUser->hasPermissionTo('userAdmin');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('userUpdate');
    }

    public function disable(User $user): bool
    {
        return $user->hasPermissionTo('userDisable');
    }

    public function enable(User $user): bool
    {
        return $user->hasPermissionTo('userEnable');
    }
}
