<?php

use App\Http\Controllers\PokeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', [PokeController::class, 'test']);

