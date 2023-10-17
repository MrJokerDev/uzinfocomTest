<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');

    Route::get('/files', [FileController::class, 'index'])->name('file.index');
    Route::post('/upload-file', [FileController::class, 'uploadFile'])->name('file.uploadFile');
    Route::delete('/file/{id}', [FileController::class, 'destroy'])->name('file.destroy');
});
