import axios from 'axios';
import { PokémonI, SingleMoveI } from '../models';

interface FiltersI{
  offset:number;
  limit:number;
  name:string;
  type:number[];
  notType:number[];
  gen:number[];
  sort:number;
  max:number;
}

const url='http://127.0.0.1:8000/';

export function getAll(filters:FiltersI,cb:(a:PokémonI)=>void):void{
  axios.get(`${url}pokemon?limit=${filters.limit}&offset=${filters.offset}&name=${filters.name}&type=${filters.type.join(',')}&notType=${filters.notType.join(',')}`)
    .then(res=>{
      const serverPokeJSON:PokémonI[] = res.data;
      serverPokeJSON.forEach(pokemon=>{
        cb(pokemon)
      })
    })
    .catch(err=>{
      console.warn("err in PokeServe.ts/getAll()");
      console.error(err);
      cb(err);
    });
}
export function getOne(identifier:string,cb:(a:PokémonI)=>void):void{
  axios.get(`${url}pokemon/${identifier}`)
    .then(res=>{
      const pokemon:PokémonI = res.data;
      const tempMoveArray:SingleMoveI[] = [...res.data.moves];
      pokemon.moves = {level:[],egg:[],machine:[],other:[]};
      pokemon.moves.level = tempMoveArray.filter((move)=>move.level>0).sort((a,b)=>{if(a.level<b.level){return -1;} else {return 1;}});
      pokemon.moves.egg = tempMoveArray.filter((move)=>move.level===0);
      pokemon.moves.machine = tempMoveArray.filter((move)=>move.level===-1);
      pokemon.moves.other = tempMoveArray.filter((move)=>move.level===-2);
      cb(pokemon);
    })
    .catch(err=>{
      console.warn("err in PokeServe.ts/getOne()");
      console.error(err);
      cb(err.message);
    });
}