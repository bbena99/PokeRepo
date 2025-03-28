import axios from "axios";
import { MovesI, SinglePokemonI } from "../models";

interface FiltersI{
  limit:number;
  offset:number;
  name:string;
  type:number[];
  notType:number[];
  damageType:number[];
  sort:number;
}

const url='http://127.0.0.1:8000/';

export function getAllMoves(filters:FiltersI,cb:(a:MovesI[])=>void,setMax:(count:number)=>void):void{
  axios.get(`${url}move?limit=${filters.limit}&offset=${filters.offset}&name=${filters.name}&type=${filters.type.join(',')}&notType=${filters.notType.join(',')}&gen=${filters.damageType.join(',')}&sort=${filters.sort}`)
    .then(res=>{
      console.log(res.data);
      const serverMoveJSON = res.data.movesArray;
      cb(serverMoveJSON);
      setMax(res.data.maxMoves);
    })
    .catch(err=>{
      console.warn("err in MoveService.ts/getAllMoves()");
      console.error(err);
      cb(err);
    });
}
export function getOneMove(id:string,cb:(a:MovesI)=>void):void{
  axios.get(`${url}move/`+id)
    .then(res=>{
      const move = res.data;
      const tempMoveArray:SinglePokemonI[] = [...res.data.pokemon];
      move.pokemon = {level:[],egg:[],machine:[],other:[]};
      move.pokemon.level = tempMoveArray.filter((move)=>move.level>0).sort((a,b)=>{return a.level>b.level?1:0;});
      move.pokemon.egg = tempMoveArray.filter((move)=>move.level===0).sort((a,b)=>{return a.id>b.id?1:0;});
      move.pokemon.machine = tempMoveArray.filter((move)=>move.level===-1).sort((a,b)=>{return a.id>b.id?1:0;});
      move.pokemon.other = tempMoveArray.filter((move)=>move.level===-2).sort((a,b)=>{return a.id>b.id?1:0;});
      console.log(move);
      cb(move);
    })
    .catch(err=>{
      console.warn("err in MoveService.ts/getOneMove()");
      console.error(err);
      cb(err.message);
    })
}