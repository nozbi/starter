<?php

use App\App;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;

Route::middleware([AuthMiddleware::class])->group(function () 
{
    new App();
});

Route::redirect('/two-factor', '/login')->name('two-factor');

Route::redirect('/password', '/login')->name('password');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
