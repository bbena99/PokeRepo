<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controller;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view(view: 'test');
});

Route::get('/test2', [Controller::class, 'test'])->name('test');
