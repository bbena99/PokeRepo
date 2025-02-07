import { PokémonI } from "./Pokémon";

export interface AbilityI{
  id:number;
  name:string;
  effect_entries:string;
  pokemon:PokémonI[];
  hiddenPokemon:PokémonI[];
}

export function emptyAbility():AbilityI{
  return {
    id:-1,
    name:"",
    effect_entries:"",
    pokemon:[],
    hiddenPokemon:[],
  }
}