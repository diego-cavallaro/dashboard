<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('help', function () {
    return view('help');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
/* ])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
}); */

])->group(function () {
    Route::get('/dashboard',[DocsController::class, 'index'])->name('dashboard');
});