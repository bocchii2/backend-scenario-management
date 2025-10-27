<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('correo_electronico', 'password');

        try {
            if (! $token = auth('api')->attempt($credentials)) {
                return response()->json(['message' => 'Credenciales inválidas'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'No se pudo crear el token'], 500);
        }
        // get user information

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        try {
            auth('api')->logout();
        } catch (JWTException $e) {
            return response()->json(['message' => 'Error al cerrar sesión, intente de nuevo'], 500);
        }

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function refresh()
    {
        try {
            $newToken = auth('api')->refresh();
        } catch (JWTException $e) {
            return response()->json(['message' => 'No se pudo refrescar el token'], 500);
        }

        return $this->respondWithToken($newToken);
    }

    protected function respondWithToken($token)
    {
        // Obtener el tiempo de expiración del token
        $ttl = auth('api')->factory()->getTTL() * 60; // Tiempo en segundos
        // Obtener información adicional del usuario
        $user = auth('api')->user();

        // Cargar relaciones adicionales
        if ($user) {
            // eager load roles con sus permisos y relaciones adicionales
            $user->load('roles', 'departamento', 'cargo');

            // permisos únicos agregados desde todos los roles del usuario
            $permissions = $user->roles
                ->flatMap(fn($role) => $role->permissions)
                ->unique('id')
                ->values();
        } else {
            // Si no hay usuario autenticado, no hay permisos
            $permissions = collect();
        }

        // Retornar la respuesta con el token y la información del usuario
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $ttl,
            'user'         => $user,
        ]);
    }

}
