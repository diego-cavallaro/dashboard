<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Certificados\CertificadoConfigDetController;
use App\Http\Controllers\Certificados\PlantillaParserController;

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
                    Route::get('/certificadoConfigDet', [CertificadoConfigDetController::class, 'index']);
                    Route::get('/Certificados/Show/{tipoCertificadoId}', [PlantillaParserController::class, 'index'])->name('certificados.show');
                    Route::post('/Certificados/Filter/{tipoCertificadoId}', [PlantillaParserController::class, 'filter'])->name('certificados.filter');
                    Route::get('/Certificados/Create/{nroPieza}/{tipoCertificadoId}', [PlantillaParserController::class, 'create'])->name('certificados.create');
                    Route::post('/Certificados/Store', [PlantillaParserController::class, 'store'])->name('certificados.store');
                    Route::get('/Certificados/Edit/{certificadoId}', [PlantillaParserController::class, 'edit'])->name('certificados.edit');
                    Route::post('/Certificados/Update/', [PlantillaParserController::class, 'update'])->name('certificados.update');
                    Route::get('/Certificados/VistaPreliminar/{certificadoId}', [PlantillaParserController::class, 'vistaPreliminar'])->name('certificados.vistaPreliminar');
                }
            );