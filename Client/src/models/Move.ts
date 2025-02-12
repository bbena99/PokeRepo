export interface MovesI{
  id:number;
  name:string;
}

export function emptyMove():MovesI{
  return {
    id:-1,
    name:""
  }
}