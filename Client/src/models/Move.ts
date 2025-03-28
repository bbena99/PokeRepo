export interface MovesI{
  id:number;
  name:string;
  machine:string|undefined;
  damage_type:number;
  effect_entry:string;
  effect_chance:number;
  pp:number;
  power:number;
  priority:number;
  accuracy:number;
  meta:any;
  type:number;
  pokemon:{
    level:SinglePokemonI[];
    egg:SinglePokemonI[];
    machine:SinglePokemonI[];
    other:SinglePokemonI[];
  }
}
export interface SinglePokemonI{
  name:string;
  id:number;
  order:number;
  is_default:number;
  level:number;
  back_sprite:string;
  front_sprite:string;
  types:number[];
}

export function emptyMove():MovesI{
  return {
    id:-1,
    name:"",
    machine:undefined,
    damage_type:-1,
    effect_entry:"",
    effect_chance:-1,
    pp:-1,
    power:-1,
    priority:-1,
    accuracy:-1,
    meta:{},
    type:-1,
    pokemon:{
      level:[],
      egg:[],
      machine:[],
      other:[]
    }
  }
}