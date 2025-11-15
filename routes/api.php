<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    PermissionController,
    RoleController,
    CargoController,
    DepartamentoCategoriaController,
    CategoriaEspacioController,
    CategoriaMobiliariosController,
    DepartamentoController,
    EspacioController,
    MobiliariosController,
    ServicioInternoController,
    UsuarioController,
    HorariosController,
    TarifasController,
    TiposEventosInternosController,
    EventosInternosController,
    BookingController,
    BookingServicioController,
    ServiciosController
};

Route::prefix("auth")->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login-microsoft', [AuthController::class, 'loginMicrosoft']);
});


Route::middleware(\App\Http\Middleware\JwtMiddleware::class)->group(function () {

    /* ---------------------------------------------------------
     |  AUTH & PROFILE
     --------------------------------------------------------- */
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    /* ---------------------------------------------------------
     |  PERMISSIONS & ROLES
     --------------------------------------------------------- */
    Route::prefix("permisos")->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/{id}', [PermissionController::class, 'show']);
        Route::post('/', [PermissionController::class, 'store']);
        Route::put('/{id}', [PermissionController::class, 'update']);
        Route::delete('/{id}', [PermissionController::class, 'destroy']);
        Route::get('/entidad/{entidad}', [PermissionController::class, 'getByEntidad']);
    });

    Route::prefix("roles")->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::get('/{id}/permisos', [RoleController::class, 'showWithPermissions']);
        Route::get('/all/permisos', [RoleController::class, 'indexWithPermissions']);

        Route::post('/', [RoleController::class, 'store']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    /* ---------------------------------------------------------
     |  CARGOS, CATEGORÍAS Y CATÁLOGOS
     --------------------------------------------------------- */
    Route::apiResource('cargos', CargoController::class);

    Route::apiResource('categoria_departamentos', DepartamentoCategoriaController::class);
    Route::apiResource('categoria_espacios', CategoriaEspacioController::class);
    Route::apiResource('categoria_mobiliarios', CategoriaMobiliariosController::class);

    /* ---------------------------------------------------------
     |  DEPARTAMENTOS (jerarquía incluida)
     --------------------------------------------------------- */
    Route::prefix("departamentos")->group(function () {
        Route::get('/', [DepartamentoController::class, 'index']);
        Route::get('/{id}', [DepartamentoController::class, 'show']);
        Route::post('/', [DepartamentoController::class, 'store']);
        Route::put('/{id}', [DepartamentoController::class, 'update']);
        Route::delete('/{id}', [DepartamentoController::class, 'destroy']);

        Route::get('/{id}/departamento_padre', [DepartamentoController::class, 'getDepartamentoPadre']);
        Route::get('/{id}/usuarios', [DepartamentoController::class, 'getUsuariosDepartamento']);

        // Jerarquía
        Route::get('/{id}/ancestros', [DepartamentoController::class, 'obtenerAncestros']);
        Route::get('/{id}/ruta', [DepartamentoController::class, 'obtenerRuta']);
        Route::get('/{id}/descendientes', [DepartamentoController::class, 'obtenerDescendientes']);
        Route::get('/{id}/raiz', [DepartamentoController::class, 'obtenerRaiz']);
        Route::get('/{id}/es-ancestro/{otro_id}', [DepartamentoController::class, 'esAncestroDE']);
    });

    /* ---------------------------------------------------------
     |  USUARIOS (roles, cargos, imagen)
     --------------------------------------------------------- */
    Route::prefix("usuarios")->group(function () {
        Route::get('/', [UsuarioController::class, 'index']);
        Route::get('/{id}', [UsuarioController::class, 'show']);
        Route::post('/', [UsuarioController::class, 'store']);
        Route::put('/{id}', [UsuarioController::class, 'update']);
        Route::delete('/{id}', [UsuarioController::class, 'destroy']);

        // Roles
        Route::get('/{usuario}/roles', [UsuarioController::class, 'getRoles']);
        Route::get('/{usuario}/roles/{role}', [UsuarioController::class, 'getRoleById']);
        Route::get('/{usuario}/roles/{role}/check', [UsuarioController::class, 'hasRole']);
        Route::post('/{usuario}/assign-role', [UsuarioController::class, 'assignRole']);
        Route::post('/{usuario}/assign-roles', [UsuarioController::class, 'assignRoles']);
        Route::delete('/{usuario}/roles/{role}', [UsuarioController::class, 'removeRole']);

        // Cargos
        Route::get('/{usuario}/cargos', [UsuarioController::class, 'getCargos']);
        Route::get('/{usuario}/cargos/{cargo}', [UsuarioController::class, 'getCargoById']);
        Route::get('/{usuario}/tiene-cargo/{cargo}', [UsuarioController::class, 'hasCargo']);
        Route::post('/{usuario}/assign-cargo', [UsuarioController::class, 'assignCargo']);
        Route::post('/{usuario}/assign-cargos', [UsuarioController::class, 'assignCargos']);
        Route::delete('/{usuario}/cargos/{cargo}', [UsuarioController::class, 'removeCargo']);

        // Imagen de perfil
        Route::post('/{usuario}/upload-profile-image', [UsuarioController::class, 'uploadProfileImage']);
        Route::get('/{usuario}/profile-image', [UsuarioController::class, 'getProfileImage']);
        Route::delete('/{usuario}/profile-image', [UsuarioController::class, 'deleteProfileImage']);
    });

    /* ---------------------------------------------------------
     |  ESPACIOS (mobiliarios, horarios, servicios internos)
     --------------------------------------------------------- */
    Route::prefix('espacios')->group(function () {

        Route::get('/', [EspacioController::class, 'index']);
        Route::post('/', [EspacioController::class, 'store']);
        Route::get('/{id}', [EspacioController::class, 'show']);
        Route::put('/{id}', [EspacioController::class, 'update']);
        Route::delete('/{id}', [EspacioController::class, 'destroy']);

        // Filtros
        Route::get('/departamento/{id}', [EspacioController::class, 'getEspaciosByDepartamento']);
        Route::get('/categoria/{id}', [EspacioController::class, 'getEspaciosByCategoria']);
        Route::get('/capacidad/{capacidad}', [EspacioController::class, 'getEspaciosConCapacidadMinima']);

        // Mobiliarios
        Route::get('/{espacio}/mobiliarios', [EspacioController::class, 'getMobiliariosByEspacio']);
        Route::post('/{espacio}/mobiliarios/asociar', [EspacioController::class, 'assignMobiliario']);
        Route::post('/{espacio}/mobiliarios/asociar-multiples', [EspacioController::class, 'assignMobiliarios']);
        Route::delete('/{espacio}/mobiliarios/desasociar', [EspacioController::class, 'removeMobiliarios']);
        Route::get('/{espacio}/mobiliarios/sincronizar', [EspacioController::class, 'syncMobiliarios']);

        // Horarios
        Route::get('/{espacio}/horarios', [EspacioController::class,'getHorarios']);
        Route::get('/{espacio}/horarios/{horario}', [EspacioController::class, 'getHorarioById']);
        Route::post('/horarios/assignar', [EspacioController::class,'assignHorario']);
        Route::post('/horarios/assignar-multiples', [EspacioController::class,'assignHorarios']);
        Route::delete('/{espacio}/horarios/remover/{horario}', [EspacioController::class,'removeHorario']);
        Route::post('/{espacio}/horarios/sincronizar', [EspacioController::class,'syncHorarios']);

        // Servicios internos
        Route::get('/{espacio}/servicios_internos', [EspacioController::class, 'getServiciosInternos']);
        Route::post('/{espacio}/servicios_internos/asignar', [EspacioController::class, 'assignServicioInterno']);
        Route::post('/{espacio}/servicios_internos/asignar-multiples', [EspacioController::class, 'assignServiciosInternos']);
        Route::delete('/{espacio}/servicios_internos/remover/{servicio}', [EspacioController::class, 'removeServicioInterno']);
        Route::post('/{espacio}/servicios_internos/sincronizar', [EspacioController::class, 'syncServiciosInternos']);
    });

    /* ---------------------------------------------------------
     |  MOBILIARIOS
     --------------------------------------------------------- */
    Route::prefix('mobiliarios')->group(function () {
        Route::get('/', [MobiliariosController::class, 'index']);
        Route::post('/', [MobiliariosController::class, 'store']);
        Route::get('/{id}', [MobiliariosController::class, 'show']);
        Route::put('/{id}', [MobiliariosController::class, 'update']);
        Route::delete('/{id}', [MobiliariosController::class, 'destroy']);

        Route::get('/categorias/{categoria}', [MobiliariosController::class, 'getMobiliariosByCategoria']);
        Route::get('/{id}/espacios', [MobiliariosController::class, 'getEspacios']);
    });

    /* ---------------------------------------------------------
     |  HORARIOS - TARIFAS - TIPOS EVENTOS - EVENTOS INTERNOS
     --------------------------------------------------------- */
    Route::apiResource('horarios', HorariosController::class);
    Route::apiResource('tarifas', TarifasController::class);
    Route::apiResource('tipos_eventos_internos', TiposEventosInternosController::class);
    Route::apiResource('eventos_internos', EventosInternosController::class);

    /* ---------------------------------------------------------
     |  SERVICIOS
     --------------------------------------------------------- */
    Route::apiResource('servicios', ServiciosController::class);

    /* ---------------------------------------------------------
     |  BOOKINGS + SERVICES
     --------------------------------------------------------- */
    Route::prefix('booking')->group(function () {
        Route::get('/', [BookingController::class,'index']);
        Route::post('/', [BookingController::class, 'store']);
        Route::get('/{id}', [BookingController::class,'show']);
        Route::put('/{id}', [BookingController::class, 'update']);
        Route::delete('/{id}', [BookingController::class, 'destroy']);

        // relación booking - servicios
        Route::post('/{booking}/servicios/asignar', [BookingServicioController::class, 'assign']);
        Route::post('/{booking}/servicios/remover', [BookingServicioController::class, 'remove']);
        Route::post('/{booking}/servicios/sincronizar', [BookingServicioController::class, 'sync']);
    });

});
