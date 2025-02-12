import axios from "axios";
import { MovesI } from "../models/Move";

interface FiltersI{
  limit:number;
  offset:number;
}

const url='http://127.0.0.1:8000/';

export function getAllMoves(filters:FiltersI,cb:(a:MovesI[])=>void):void{
  axios.get(`${url}move?limit=${filters.limit}&offset=${filters.offset}`)
    .then(res=>{
      const serverPokeJSON = res.data;
      cb(serverPokeJSON);
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
      console.log(move)
      cb(move);
    })
    .catch(err=>{
      console.warn("err in MoveService.ts/getOneMove()");
      console.error(err);
      cb(err.message);
    })
}