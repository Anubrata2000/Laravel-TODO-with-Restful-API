<?php

use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\TodoWebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Root redirect
Route::get( '/', function () {
    return redirect()->route( 'web.todos.index' );
} );

// Guest Web Authentication Routes
Route::middleware( 'guest' )->group( function () {
    Route::get( '/login', [AuthWebController::class, 'showLoginForm'] )->name( 'login' );
    Route::post( '/login', [AuthWebController::class, 'login'] );
    Route::get( '/register', [AuthWebController::class, 'showRegisterForm'] )->name( 'register' );
    Route::post( '/register', [AuthWebController::class, 'register'] );
} );

// Authenticated Web Application Routes
Route::middleware( 'auth' )->group( function () {
    Route::post( '/logout', [AuthWebController::class, 'logout'] )->name( 'logout' );

    // Todo Web Dashboard
    Route::get( '/todos', [TodoWebController::class, 'index'] )->name( 'web.todos.index' );

    // Profile Settings
    Route::get( '/profile', [TodoWebController::class, 'profile'] )->name( 'profile' );
    Route::post( '/profile', [TodoWebController::class, 'updateProfile'] )->name( 'profile.update' );
} );
