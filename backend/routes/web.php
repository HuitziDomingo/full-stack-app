<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de usuarios (CRUD completo)
Route::get('/users', [UsersController::class, 'index']);           // Listar todos los usuarios (con paginación)
Route::get('/users/count', [UsersController::class, 'count']);    // Contar usuarios (total, verificados, no verificados)
Route::post('/users', [UsersController::class, 'store']);         // Crear un nuevo usuario
Route::get('/users/{id}', [UsersController::class, 'show']);       // Ver un usuario específico
Route::put('/users/{id}', [UsersController::class, 'update']);     // Actualizar un usuario
Route::delete('/users/{id}', [UsersController::class, 'destroy']); // Eliminar un usuario