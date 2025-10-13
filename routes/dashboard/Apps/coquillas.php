<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Coquillas\CoquillaController;
use App\Http\Controllers\Coquillas\GrupoController;

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
                    Route::get('/Coquillas/Show', [CoquillaController::class, 'index'])->name('coquillas.show');
                    Route::post('/Coquillas/Filter/', [CoquillaController::class, 'filter'])->name('coquillas.filter');
                    Route::get('/Coquillas/Create/', [CoquillaController::class, 'create'])->name('coquillas.create');
                    Route::post('/Coquillas/Store', [CoquillaController::class, 'store'])->name('coquillas.store');
                    Route::get('/Coquillas/Edit/{resourceId}', [CoquillaController::class, 'edit'])->name('coquillas.edit');
                    Route::post('/Coquillas/Update/', [CoquillaController::class, 'update'])->name('coquillas.update');

                    Route::post('/Coquillas/Grupo/Create/{resourceId}/{groupId}', [CoquillaController::class, 'storeGrupo'])->name('coquillas.storeGrupo');
                    Route::get('/Coquillas/Grupo/Delete/{resourceId}/{groupId}', [CoquillaController::class, 'destroyGrupo'])->name('coquillas.destroyGrupo');

                    Route::get('/Grupos/Show', [GrupoController::class, 'index'])->name('grupos.show');
                    Route::get('/Grupos/Create/', [GrupoController::class, 'create'])->name('grupos.create');
                    Route::post('/Grupos/Store', [GrupoController::class, 'store'])->name('grupos.store');
                    Route::get('/Grupos/Edit/{resourceId}', [GrupoController::class, 'edit'])->name('grupos.edit');
                    Route::post('/Grupos/Update/', [GrupoController::class, 'update'])->name('grupos.update');

                    // Route::get('/Certificados/VistaPreliminar/{certificadoId}', [PlantillaParserController::class, 'vistaPreliminar'])->name('certificados.vistaPreliminar');
                }
            );