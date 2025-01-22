<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\SignInController;
use Illuminate\Support\Facades\Route;

// SignIn Routes
Route::prefix('sign-in')->group(function () {
    Route::get('/', [SignInController::class, 'index'])->name('sign_in.index');
    Route::get('/fetch', [SignInController::class, 'fetch'])->name('sign_in.fetch');
    Route::post('/create', [SignInController::class, 'create'])->name('sign_in.create');
    Route::post('/delete', [SignInController::class, 'delete'])->name('sign_in.delete');
    Route::get('/get', [SignInController::class, 'get'])->name('sign_in.get');
    Route::post('/update', [SignInController::class, 'update'])->name('sign_in.update');
    Route::get('/update-view/{id}', [SignInController::class, 'update_view'])->name('signin.update_view');
    Route::post('/pdf', [SignInController::class, 'pdf'])->name('sign_in.pdf');
});

// News Routes
Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news.index');
    Route::get('/fetch', [NewsController::class, 'fetch'])->name('news.fetch');
    Route::get('/published', [NewsController::class, 'getPublished'])->name('news.published');
    Route::get('/{id}/update-view', [NewsController::class, 'update_view'])->name('news.update_view');
    Route::post('/create', [NewsController::class, 'store'])->name('news.store');
    Route::put('/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
    Route::post('/delete', [NewsController::class, 'destroy'])->name('news.delete');
    Route::post('/get', [NewsController::class, 'get'])->name('news.get');
});
