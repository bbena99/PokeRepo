<?php

use PokePHP\PokeApi;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controller;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    $current = new PokeAPI();
    info(print_r($current->pokemon('10')));
    // $test = 'testing string';
    // return view(view: 'test');
});

Route::get('/test2', [Controller::class, 'test'])->name('test2.index');
