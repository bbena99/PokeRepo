export interface TypeI {
  damage_relations:{
    double_damage_from:number[];
    double_damage_to:number[];
    half_damage_from:number[];
    half_damage_to:number[];
    no_damage_from:number[];
    no_damage_to:number[];
  };
  id:number;
  name:string;
}