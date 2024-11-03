<?php

use App\Http\Controllers\PokeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/pokemon', [PokeController::class, 'getAll']);
Route::get('/pokemon/{identifier}', [PokeController::class, 'getOne']);
Route::get('/{parse}/{identifier}', [PokeController::class, 'getResource']);

