<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('student')->group(function () {
    Route::get('/', [StudentController::class, "index"])->name('student.index');
    Route::get('/get', [StudentController::class, 'getStudent'])->name('student.get');
    Route::post('/create', [StudentController::class, "createStudent"])->name('student.create');
    Route::get('/fetch', [StudentController::class, 'fetchDataTable'])->name('student.fetch');
    Route::post('/update', [StudentController::class, 'updateStudent'])->name('student.update');
    Route::post('/delete', [StudentController::class, 'deleteStudent'])->name('student.delete');
});

Route::get('/package-test', [App\Http\Controllers\PackageController::class, 'index'])->name('package.index');
Route::get('/package-test/fetch', [App\Http\Controllers\PackageController::class, 'fetch'])->name('package.fetch');
