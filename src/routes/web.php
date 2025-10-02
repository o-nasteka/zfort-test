<?php

use App\Http\Controllers\ActorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('actors.form');
});

Route::get('/actors/form', [ActorController::class, 'showForm'])->name('actors.form');
Route::post('/actors', [ActorController::class, 'store'])->name('actors.store');
Route::get('/actors/table', [ActorController::class, 'showTable'])->name('actors.table');
