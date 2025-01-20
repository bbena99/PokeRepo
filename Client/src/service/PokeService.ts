import axios from 'axios';
import { PokémonI, SingleMoveI } from '../models';

interface FiltersI{
  offset:number;
  limit:number;
  color?:number;
  gen?:number;
  type?:number;
}

const url='http://127.0.0.1:8000/';

export function getAll(filters:FiltersI,cb:(a:PokémonI)=>void):void{
  axios.get(`${url}pokemon?limit=${filters.limit}&offset=${filters.offset}`)
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
      const newMoveObj:{level:SingleMoveI[],egg:SingleMoveI[],other:SingleMoveI[]} = {level:[],egg:[],other:[]};
      console.log(tempMoveArray.filter((move)=>move.level>0).sort((a,b)=>{return a.level>b.level?1:0;}));
      newMoveObj.level = tempMoveArray.filter((move)=>move.level>0).sort((a,b)=>{return a.level>b.level?1:0;});
      newMoveObj.egg = tempMoveArray.filter((move)=>move.level===0).sort((a,b)=>{return a.id>b.id?1:0;});
      newMoveObj.other = tempMoveArray.filter((move)=>move.level<0).sort((a,b)=>{return a.id>b.id?1:0;});
      console.log(newMoveObj)
      pokemon.moves=newMoveObj;
      console.log(pokemon)
      cb(pokemon);
    })
    .catch(err=>{
      console.warn("err in PokeServe.ts/getOne()");
      console.error(err);
      cb(err.message);
    });
}