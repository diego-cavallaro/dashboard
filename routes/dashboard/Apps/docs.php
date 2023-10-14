<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocsController;

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
                    Route::get('/docs/public', [DocsController::class, 'index'])->name('docs.public');
                    Route::get('/docs/show/{doc}', [DocsController::class, 'show'])->name('docs.show');
                    Route::get('/docs/areaShow/{area}', [DocsController::class, 'areaShow'])->name('docs.areaShow');
                    Route::get('/docs/tagShow/{tag}', [DocsController::class, 'tagShow'])->name('docs.tagShow');
                    Route::get('/docs/list', [DocsController::class, 'list'])->name('docs.list');
                    Route::get('/docs/edit/{doc}', [DocsController::class, 'edit'])->name('docs.edit');
                    Route::put('/docs/store', [DocsController::class, 'store'])->name('docs.store');
                    Route::put('/docs/update/{doc}', [DocsController::class, 'update'])->name('docs.update');
                    Route::delete('/docs/destroy/{doc}', [DocsController::class, 'destroy'])->name('docs.destroy');
                    //Route::get('/test/sqlpdo', [DocsController::class, 'MSsqlpdo'])->name('test.sqlpdo');
                    //Route::get('/test/sqldrv', [DocsController::class, 'MSsqldrv'])->name('test.sqldrv');
                }
        );