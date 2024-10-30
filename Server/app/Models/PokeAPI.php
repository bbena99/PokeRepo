<?php

namespace App\Models;

use PokePHP\PokeApi as PokePHPPokeApi;


class PokeAPI extends PokePHPPokeApi{
    protected PokePHPPokeApi $client;
    public function __construct(
    ){
        $this->client = new PokePHPPokeApi();
    }
}
