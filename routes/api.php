<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public Authentication & Registration Routes
Route::post( '/register', [UserController::class, 'store'] )->name( 'register' );
Route::post( '/users', [UserController::class, 'store'] );
Route::post( '/login', [UserController::class, 'login'] )->name( 'login' );

// Protected Routes (Requires Sanctum Authentication Token)
Route::middleware( 'auth:sanctum' )->group( function () {

    // Authenticated User Profile & Logout
    Route::get( '/user', [UserController::class, 'profile'] );
    Route::get( '/user/profile', [UserController::class, 'profile'] )->name( 'profile' );
    Route::put( '/user/profile', [UserController::class, 'updateProfile'] )->name( 'profile.update' );
    Route::post( '/logout', [UserController::class, 'logout'] )->name( 'logout' );

    // Todo Management Routes (Scoped to authenticated user)
    Route::get( '/todos', [TodoController::class, 'index'] )->name( 'todos.index' );
    Route::post( '/todos', [TodoController::class, 'store'] )->name( 'todos.store' );
    Route::get( '/todos/{id}', [TodoController::class, 'show'] )->name( 'todos.show' );
    Route::put( '/todos/{id}', [TodoController::class, 'update'] )->name( 'todos.update' );
    Route::patch( '/todos/{id}/status', [TodoController::class, 'updateStatus'] )->name( 'todos.updateStatus' );
    Route::delete( '/todos/{id}', [TodoController::class, 'destroy'] )->name( 'todos.destroy' );

    // User Administration Routes
    Route::get( '/users', [UserController::class, 'index'] )->name( 'users.index' );
    Route::get( '/users/{id}', [UserController::class, 'show'] )->name( 'users.show' );
    Route::put( '/users/{id}', [UserController::class, 'update'] )->name( 'users.update' );
    Route::delete( '/users/{id}', [UserController::class, 'destroy'] )->name( 'users.destroy' );
} );
