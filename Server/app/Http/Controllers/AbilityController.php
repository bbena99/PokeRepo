<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbilityController{
  protected $output;
  protected $limit;
  protected $offset;

  public function __construct(){
    $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
  }

  public function getAll(Request $request){
  }
  public function getOne(Request $request){

  }
}
