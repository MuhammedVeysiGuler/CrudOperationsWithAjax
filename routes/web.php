<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;



Route::prefix('student')->group(function () {
    Route::get('/', [StudentController::class, "index"])->name('student.index');
    Route::post('/create', [StudentController::class, "create"])->name('student.create');
    Route::get('/fetch', [StudentController::class, 'fetch'])->name('student.fetch');
    Route::post('/delete', [StudentController::class, 'delete'])->name('student.delete');
    Route::get('/get', [StudentController::class, 'get'])->name('student.get');
    Route::post('/update', [StudentController::class, 'update'])->name('student.update');
});
