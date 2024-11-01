import axios from 'axios';

export function getOne(identifier:string,cb:(a:any)=>void):void{
  axios.get(`http://127.0.0.1:8000/test?id=${identifier}`)
  .then(res=>{
    cb(JSON.parse(res.data));
  })
  .catch(err=>{
    console.warn("err in PokeServe.ts/getAll()");
    console.error(err);
    cb(err);
  });
}