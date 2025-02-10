import axios from "axios";
import { AbilityI } from "../models";

interface FiltersI{
  limit:number;
  offset:number;
}

const url='http://127.0.0.1:8000/';

export function getAllAbilities(filters:FiltersI,cb:(a:AbilityI[])=>void):void{
  axios.get(`${url}ability?limit=${filters.limit}&offset=${filters.offset}`)
    .then(res=>{
      const serverPokeJSON:AbilityI[] = res.data;
      cb(serverPokeJSON);
    })
    .catch(err=>{
      console.warn("err in AbilityService.ts/getAll()");
      console.error(err);
      cb(err);
    });
}
export function getOneAbility(id:string,cb:(a:AbilityI)=>void):void{
  axios.get(`${url}ability/`+id)
    .then(res=>{
      console.log(res.data);
      const ability:AbilityI = res.data;
      cb(ability);
    })
    .catch(err=>{
      console.warn("err in AbilityService.ts/getOneAbility()");
      console.error(err);
      cb(err.message);
    })
}