export interface PokémonI{
  id:number;
  name:string;
  is_default:boolean;
  order:number;
  front_sprite:string;
  back_sprite:string;
  types:{
    id:number;
    name:string;
    src:string;
    relations:number[];
  }[];
  stats:{
    "hp":number;
    "attack":number;
    "defense":number;
    "special-attack":number;
    "special-defense":number;
    "speed":number;
  }
  abilities:{
    id:number;
    name:string;
    hidden:boolean;
    effect_entries:string;
  }[];
  moves:{
    level:SingleMoveI[];
    machine:SingleMoveI[];
    other:SingleMoveI[];
  }[];
}
interface SingleMoveI {
  id:number;
  name:string;
  damage_type:number;
  accuracy:number;
  power:number;
  pp:number;
  level:number;

}

export function emptyPokemon():PokémonI{
  return {
    id:-1,
    name:'',
    is_default:false,
    order:-1,
    front_sprite:'',
    back_sprite:'',
    types:[],
    stats:{
      "hp":-1,
      "attack":-1,
      "defense":-1,
      "special-attack":-1,
      "special-defense":-1,
      "speed":-1
    },
    abilities:[],
    moves:[]
  }
}