<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortenedUrlController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/utilty', function () {
    return redirect('/');
});

Route::post('/shorten-url', [ShortenedUrlController::class, 'store']);

Route::get('/{code}', [ShortenedUrlController::class, 'get']);
