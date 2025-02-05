<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\dbgen;
use App\Http\Controllers\MoveController;
use App\Http\Controllers\PokeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/initdb/{key}', [dbgen::class, 'initDb']);
Route::get('/pokemon', [PokeController::class, 'getAll']);
Route::get('/pokemon/{identifier}', [PokeController::class, 'getOne']);
Route::get('/ability', [AbilityController::class, 'getAll']);
Route::get('/ability/{identifier}',[AbilityController::class, 'getOne']);
Route::get('/move', [MoveController::class, 'getAll']);
Route::get('/move/{identifier}',[MoveController::class, 'getOne']);
