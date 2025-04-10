<?php

use App\Http\Controllers\PostController;
use App\Http\Middleware\PostOwnership;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');
Route::inertia('dashboard', 'Dashboard')->middleware([ 'auth' ])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/posts', [ PostController::class, 'store' ])->name('posts.create');
    Route::get('/posts', [ PostController::class, 'list' ])->name('posts.list');

    // protected post routes
    Route::middleware(PostOwnership::class)->group(function(){
        Route::get('/posts/{post}', [ PostController::class, 'show' ])->name('posts.show');
        Route::patch('/posts/{post}', [ PostController::class, 'patch' ])->name('posts.update');
        Route::delete('/posts/{post}', [ PostController::class, 'delete' ])->name('posts.delete');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
