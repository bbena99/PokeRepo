import axios from 'axios';
import { PokémonI, standardPair } from '../models';

interface FiltersI{
  offset:number;
  limit:number;
  color?:number;
  gen?:number;
  type?:number;
}

const url='http://127.0.0.1:8000/'

export function getResource(resource:standardPair,cb:(a:any)=>void){
  console.log(typeof(resource))
  const splitUrl = resource.url.split('/');
  splitUrl.pop()
  const identifier = splitUrl.pop();
  const parse = splitUrl.pop();
  getParse(parse!,identifier!,cb);
}
export function getParse(parse:string,identifier:string,cb:(a:any)=>void){
  axios.get(`${url}${parse}/${identifier}`)
  .then(res=>{
    cb(JSON.parse(res.data));
  })
  .catch(err=>{
    console.warn("err in PokeServe.ts/getResource()");
    console.error(err);
    cb(err);
  });
}
export function getAll(filters:FiltersI,cb:(a:any)=>void):void{
  axios.get(`${url}pokemon?limit=${filters.limit}&offset=${filters.offset}`)
  .then(res=>{
    cb(JSON.parse(res.data));
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
    console.warn("err in PokeServe.ts/getAll()");
    console.error(err);
    cb(err);
  });
}