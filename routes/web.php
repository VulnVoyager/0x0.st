<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

// Исключаем маршрут загрузки из CSRF защиты
Route::post('/', [FileController::class, 'upload'])->withoutMiddleware(['web']);

Route::get('/file/{hash}', [FileController::class, 'download']);
Route::get('/delete/{token}', [FileController::class, 'delete']);
Route::post('/delete/{token}', [FileController::class, 'delete'])->withoutMiddleware(['web']);