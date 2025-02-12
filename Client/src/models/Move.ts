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
  }
}