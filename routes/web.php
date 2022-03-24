<?php

use App\Http\Controllers\SignInController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SignInController::class, "index"])->name('sign_in.index');
Route::post('/create', [SignInController::class, "create"])->name('sign_in.create');
Route::get('/fetch', [SignInController::class, 'fetch'])->name('sign_in.fetch');
Route::post('/delete', [SignInController::class, 'delete'])->name('sign_in.delete');
