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
  }[];
  stats:{
    hp:number;
    attack:number;
    defense:number;
    'special-attack':number;
    'special-defense':number;
    speed:number;
    total?:number;
  }
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
      hp:-1,
      attack:-1,
      defense:-1,
      'special-attack':-1,
      'special-defense':-1,
      speed:-1,
      total:-1
    }
  }
}