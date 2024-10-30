<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
abstract class Controller
{
    public function test(){
        return view("test");
    }
}
