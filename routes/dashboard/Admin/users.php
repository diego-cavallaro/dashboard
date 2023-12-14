<?php

use App\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersRolesController;

Route::middleware
    (
        [
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified'
        ]
    )->group
        (
            function () 
                {

                    Route::get('/users/index', [UsersController::class, 'index'])->name('users.list');
                    Route::get('/users/show/{user}', [UsersController::class, 'show'])->name('users.show');
                    Route::get('/users/edit/{user}', [UsersController::class, 'edit'])->name('users.edit');
                    Route::put('/users/update/{user}', [UsersController::class, 'update'])->name('users.update');
                    Route::get('/users/disable', [UsersController::class, 'disable'])->name('users.disable');
                    Route::put('/users/{user}/roles',[UsersRolesController::class, 'update'])->name('users.roles.update');
                }
        );