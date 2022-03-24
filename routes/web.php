<?php

use App\Http\Controllers\SignInController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [SignInController::class, "index"])->name('sign_in.index');
Route::post('/create', [SignInController::class, "create"])->name('sign_in.create');
Route::get('/fetch', [SignInController::class, 'fetch'])->name('sign_in.fetch');
