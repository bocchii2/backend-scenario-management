<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Validar datos del registro manual
        $request->validate([
            'nombres_completos' => 'required|string|max:255',
            'correo_electronico' => 'required|email|max:255|unique:usuarios',
            'numero_identificacion' => 'required|string|max:50|unique:usuarios,identificacion',
            'password' => 'required|string|min:6',
        ]);

        try {
            $usuario = Usuario::create([
                'nombres_completos' => $request->nombres_completos,
                'correo_electronico' => $request->correo_electronico,
                'identificacion' => $request->numero_identificacion,
                'tipo_identificacion' => 'Cédula',
                'password' => bcrypt($request->password),
                'activo' => true,
                'cargo_id' => null,
                'departamento_id' => null,
            ]);

            // Generar token
            if ($token = auth('api')->attempt([
                'correo_electronico' => $usuario->correo_electronico,
                'password' => $request->password,
            ])) {
                return response()->json([
                    'message' => 'Usuario registrado exitosamente',
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'user' => auth('api')->user()->load('roles', 'departamento', 'cargo'),
                ], 201);
            }

            return response()->json([
                'message' => 'Usuario registrado pero no se pudo generar token',
                'user' => $usuario,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar: ' . $e->getMessage()], 500);
        }
    }

    public function loginMicrosoft(Request $request)
    {
        $request->validate([
            'nombres_completos' => 'required|string|max:255',
            'correo_electronico' => 'required|email|max:255',
            'numero_identificacion' => 'required|string|max:50',
        ]);

        try {
            $tempPassword = $request->numero_identificacion; // Define aquí
            $usuarioExistente = Usuario::where('identificacion', $request->numero_identificacion)->first();

            if ($usuarioExistente) {
                $usuarioExistente->update([
                    'correo_electronico' => $request->correo_electronico,
                    'nombres_completos' => $request->nombres_completos,
                ]);

                $usuarioExistente->update(['password' => bcrypt($tempPassword)]);

                if ($token = auth('api')->attempt([
                    'correo_electronico' => $usuarioExistente->correo_electronico,
                    'password' => $tempPassword,
                ])) {
                    return response()->json([
                        'message' => 'Usuario ya existe. Acceso concedido.',
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'temporary_password' => $tempPassword,
                        'user' => auth('api')->user()->load('roles', 'departamento', 'cargos'),
                    ], 200);
                }
            }

            // Si no existe, crear nuevo usuario con contraseña temporal

            $usuario = Usuario::create([
                'nombres_completos' => $request->nombres_completos,
                'correo_electronico' => $request->correo_electronico,
                'identificacion' => $request->numero_identificacion,
                'tipo_identificacion' => 'Cédula',
                'password' => bcrypt($request->numero_identificacion),
                'activo' => true,
                'cargo_id' => null,
                'departamento_id' => null,
            ]);

            // Generar token
            if ($token = auth('api')->attempt([
                'correo_electronico' => $usuario->correo_electronico,
                'password' => $tempPassword,
            ])) {
                return response()->json([
                    'message' => 'Usuario registrado vía Microsoft. Acceso concedido.',
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'temporary_password' => $tempPassword,
                    'user' => auth('api')->user()->load('roles', 'departamento', 'cargos'),
                ], 201);
            }

            return response()->json([
                'message' => 'Usuario registrado pero no se pudo generar token',
                'user' => $usuario,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en login Microsoft: ' . $e->getMessage()], 500);
        }
    }

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
            $user->load('roles', 'departamento', 'cargos');

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
