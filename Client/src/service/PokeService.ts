import axios from 'axios';

export function getAll(cb:(a:any)=>void):void{
  axios.get('http://127.0.0.1:8000/test')
  .then(res=>{
    console.log(res);
    const jsonObj = JSON.parse(res.data);
    cb(jsonObj);
  })
  .catch(err=>{
    console.warn("err in PokeServe.ts/getAll()");
    console.error(err);
    cb(err);
  });
}