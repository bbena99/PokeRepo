import axios from 'axios';
import { PokémonI } from '../models';

interface FiltersI{
  offset:number;
  limit:number;
  color?:number;
  gen?:number;
  type?:number;
}

const url='http://127.0.0.1:8000/'

export function getParse(parse:string,identifier:string,cb:(a:any)=>void){
  axios.get(`${url}${parse}/${identifier}`)
  .then(res=>{
    console.log(res.data)
    cb(JSON.parse(res.data));
  })
  .catch(err=>{
    console.warn("err in PokeServe.ts/getResource()");
    console.error(err);
    cb(err);
  });
}
export function getAll(filters:FiltersI,cb:(a:PokémonI)=>void):void{
  axios.get(`${url}pokemon?limit=${filters.limit}&offset=${filters.offset}`)
    .then(res=>{
      const serverPokeJSON:PokémonI[] = res.data;
      serverPokeJSON.forEach(pokemon=>{
        const newTypes:{id:number,name:string,src:string}[] = [];
        Object.keys(pokemon.types).forEach(value=>{
          //@ts-ignore
          newTypes.push(pokemon.types[+value][0]);
        })
        pokemon.types=newTypes;
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
      cb(JSON.parse(res.data));
    })
    .catch(err=>{
      console.warn("err in PokeServe.ts/getOne()");
      console.error(err);
      cb(err);
    });
}