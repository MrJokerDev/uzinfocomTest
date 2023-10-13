<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/files', [FileController::class, 'index']);
    Route::post('/upload-file', [FileController::class, 'uploadFile']);
    Route::delete('/file/{id}', [FileController::class, 'destroy']);
});
