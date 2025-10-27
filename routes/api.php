<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\DepartamentoCategoriaController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsuarioController;
use App\Models\DepartamentoCategoria;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(["prefix"=> "auth"], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/hello', function () {
    return response()->json(['message' => 'Hello, world!']);
    });
});

Route::middleware(\App\Http\Middleware\JwtMiddleware::class)->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/permisos', [PermissionController::class, 'index']);
    Route::get('/permisos/{id}', [PermissionController::class, 'show']);
    Route::post('/permisos', [PermissionController::class, 'store']);
    Route::put('/permisos/{id}', [PermissionController::class, 'update']);
    Route::delete('/permisos/{id}', [PermissionController::class, 'destroy']);
    Route::get('/permisos/entidad/{entidad}', [PermissionController::class, 'getByEntidad']);


    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);
    Route::get('/roles_permisos', [RoleController::class, 'indexWithPermissions']);
    Route::get('/roles/{id}/permisos', [RoleController::class, 'showWithPermissions']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

    Route::get('/cargos', [CargoController::class, 'index']);
    Route::get('/cargos/{id}', [CargoController::class, 'show']);
    Route::post('/cargos', [CargoController::class, 'store']);
    Route::put('/cargos/{id}', [CargoController::class, 'update']);
    Route::delete('/cargos/{id}', [CargoController::class, 'destroy']);

    Route::get('/categoria_departamentos', [DepartamentoCategoriaController::class, 'index']);
    Route::get('/categoria_departamentos/{id}', [DepartamentoCategoriaController::class, 'show']);
    Route::post('/categoria_departamentos', [DepartamentoCategoriaController::class, 'store']);
    Route::put('/categoria_departamentos/{id}', [DepartamentoCategoriaController::class, 'update']);
    Route::delete('/categoria_departamentos/{id}', [DepartamentoCategoriaController::class, 'destroy']);

    Route::get('/departamentos', [DepartamentoController::class, 'index']);
    Route::get('/departamentos/{id}', [DepartamentoController::class, 'show']);
    Route::post('/departamentos', [DepartamentoController::class, 'store']);
    Route::put('/departamentos/{id}', [DepartamentoController::class, 'update']);
    Route::delete('/departamentos/{id}', [DepartamentoController::class, 'destroy']);
    Route::get('/departamento/{id}/departamento_padre', [DepartamentoController::class, 'getDepartamentoPadre']);
    Route::get('/departamento/{id}/usuarios', [DepartamentoController::class, 'getUsuariosDepartamento']);


    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
    Route::post('/usuarios', [UsuarioController::class, 'store']);
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
    Route::get('/usuarios/{id}/roles', [UsuarioController::class, 'getRoles']);
});
