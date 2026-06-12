<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    //Afecta a filament. A otro controlador solo si explicitamente es llamado con $this->authorize('update', $user); por ej.
    //o con el método can de usuario autenticado o el middleware can en las rutas.
    // $user es el que está logueado manipulando datos
    // $model es sobre la fila que se está trabajando
    /**
     * Determina cuando el usuario puede ver todos los modelos. (Solo el admin puede ver todos los usuarios)
     */
    public function viewAny(User $user): bool
    {
        return $user->role?->name === 'admin';
    }

    /**
     * Determina quien puede ver el modelo
     */
    public function view(User $user, User $model): bool
    {
        //con ? me aseguro de no romper el código por si en pruebas creo un user sin rol. (entonces null === 'admin' da false)
        return $user->role?->name === 'admin'; // Al estar restringido al rol 'admin', ningún cliente externo podría adivinar la URL e intentar espiar los datos privados de otro usuario.
    }

    /**
     * Determina en que casos puede crearse el modelo
     * en true para que los clientes puedan registrarse en la web pública.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determina cuando el usuario puede actualizar el modelo
     */
    public function update(User $user, User $model): bool
    {
        /*
        // 1. Si el usuario que intenta editar no es administrador, se le niega el acceso
        if ($user->role?->name !== 'admin') {
            return false;
        }

        // 2. PROTECCIÓN SEMILLA: Si se está intentando editar al admin ppal (ID 1)...
        if ($model->id === 1) {
            // solo permitimos la edición si el que está logueado ($user) es el mismísimo ID 1
            return $user->id === 1;
        }

        // 3. Si pasó los filtros anteriores, es un admin editando a cualquier otro usuario
        return true;
        */
        return false;
    }

    /**
     * Determina cuando el usuario puede eliminar el modelo
     * El softdelete está permitido en filament con el método activado getEloquentQuery
     *  incluyendo el SoftDeletingScope en UserResource
     */
    public function delete(User $user, User $model): bool
    {
        /*
        // 1. Solo administradores
        if ($user->role?->name !== 'admin') {
            return false;
        }

        // 2. No puedes borrarte a ti mismo
        if ($user->id === $model->id) {
            return false;
        }

        // 3. El ID 1 es intocable
        if ($model->id === 1) {
            return false;
        }

        return true;
        */
        return false;
    }

    /**
     * Determina cuando el usuario puede restaurar el modelo
     */
    public function restore(User $user, User $model): bool
    {
        return $user->role?->name === 'admin'; // PARA PROBAR RESTAURAR USUARIO DESDE PANEL ADMIN
    }

    /**
     * Determina el borrado permanente del modelo
     */
    public function forceDelete(User $user, User $model): bool
    {
        /*

        // 1. Solo administradores
        if ($user->role?->name !== 'admin') {
            return false;
        }

        // 2. No puedes borrarte a ti mismo
        if ($user->id === $model->id) {
            return false;
        }

        // 3. El ID 1 es intocable
        if ($model->id === 1) {
            return false;
        }

        return true;
        */
        return false;
    }
}
