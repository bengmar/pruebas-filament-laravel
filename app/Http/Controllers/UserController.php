<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\User;
use App\Models\UserAddress;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {

        $user = Auth::user(); // Trae el usuario autenticado

        $provincias = Province::orderBy('name')->get(); // Traemos todas las provincias para el primer select

        return view('pages.private.user-panel', compact('user', 'provincias'));
    }

    public function update(UpdateProfileRequest $request)
    {

        $user = User::find(Auth::id()); //trae desde la base de datos, los datos de usuario autenticado

        $profile = $request->validated();

        $user->first_name = $profile['first_name'];
        $user->last_name  = $profile['last_name'];
        $user->email      = $profile['email'];

        if (!empty($profile['password'])) {
            $user->password = Hash::make($profile['password']);
        }

        $user->save();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['phone' => $profile['phone'] ?? null]
        );

        // 3. Procesar Direcciones Dinámicas
        $inputAddresses = $profile['addresses'] ?? [];
        $keepAddressIds = [];

        // Detectar si el usuario marcó alguna de las direcciones enviadas como predeterminada
        $hasNewDefault = false;
        foreach ($inputAddresses as $key => $addressData) {
            if (isset($addressData['is_default']) && $addressData['is_default'] == '1') {
                $hasNewDefault = true;
                break;
            }
        }

        // Si envió una nueva predeterminada, limpiamos todas las de la Base de Datos primero
        if ($hasNewDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        foreach ($inputAddresses as $addressData) {
            // Si ya establecimos que hay una nueva por defecto, cualquier otra de este bucle
            // que NO tenga el '1' explícito, la forzamos a false.
            if ($hasNewDefault && (!isset($addressData['is_default']) || $addressData['is_default'] != '1')) {
                $addressData['is_default'] = false;
            } else {
                $addressData['is_default'] = isset($addressData['is_default']) && $addressData['is_default'] == '1';
            }

            if (!empty($addressData['id'])) {
                // Editar una direccion existente
                $address = $user->addresses()->find($addressData['id']);
                if ($address) {
                    $address->update($addressData);
                    $keepAddressIds[] = $address->id;
                }
            } else {
                // Creamos nueva direccion
                $newAddress = $user->addresses()->create($addressData);
                $keepAddressIds[] = $newAddress->id;
            }
        }

        // 4. Eliminar las direcciones que el usuario borró en la interfaz
        $user->addresses()->whereNotIn('id', $keepAddressIds)->delete();

        return redirect()->route('panel-usuario')->with('success', '¡Datos actualizados correctamente!');
    }

    // Actúa como una mini-API para el JavaScript. Para devolver las ciudades de la provincia seleccionada
    public function getCitiesByProvince(Province $province)
    {
        // Devuelve las ciudades de la provincia seleccionada en formato JSON
        return response()->json($province->cities()->orderBy('name')->get());
    }
    //Eliminación de cuenta
    public function destroy()
    {
        $user = User::find(Auth::id());

        // 1. Desautenticar al usuario antes de borrarlo
        Auth::logout();

        // 2. Aplicar el Soft Delete
        $user->delete();

        // 3. Invalidar la sesión por seguridad y redirigir
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Tu cuenta ha sido eliminada correctamente.');
    }
}
