import axios from 'axios';

interface FiltersI{
  offset:number;
  limit:number;
  color?:number;
  gen?:number;
  type?:number;
}

export function getAll(filters:FiltersI,cb:(a:any)=>void):void{
  axios.get(`http://127.0.0.1:8000/pokemon?limit=${filters.limit}&offset=${filters.offset}`)
  .then(res=>{
    cb(JSON.parse(res.data));
  })
  .catch(err=>{
    console.warn("err in PokeServe.ts/getAll()");
    console.error(err);
    cb(err);
  });
}
export function getOne(identifier:string,cb:(a:any)=>void):void{
  axios.get(`http://127.0.0.1:8000/pokemon/${identifier}`)
  .then(res=>{
    cb(JSON.parse(res.data));
  })
  .catch(err=>{
    console.warn("err in PokeServe.ts/getAll()");
    console.error(err);
    cb(err);
  });
}