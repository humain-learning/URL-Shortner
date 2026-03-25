<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortenedUrlController;

Route::get('/', function () {
    return redirect()->away('https://nationalroadsafetymission.org/');
});


Route::get('/utilty', function () {
    return view('welcome');
});

Route::get('/open', [\App\Http\Controllers\OpenController::class, 'index']);
Route::get('/student/{id}', [\App\Http\Controllers\StudentController::class, 'index']);



Route::post('/shorten-url', [ShortenedUrlController::class, 'store']);
Route::get('/get-encoded-list', [\App\Http\Controllers\StudentController::class, 'get_encodedList']);


Route::get('/{code}', [ShortenedUrlController::class, 'get']);
