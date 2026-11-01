const fs=require('fs'),path=require('path');
const [src,out,...want]=process.argv.slice(2);
const fd=fs.openSync(src,'r');let pos=0;const h=Buffer.alloc(4377);
const z=b=>{const i=b.indexOf(0);return b.subarray(0,i<0?b.length:i).toString()};
while(fs.readSync(fd,h,0,4377,pos)===4377&&h[0]!==0){
 const name=z(h.subarray(0,255)),size=parseInt(z(h.subarray(255,269))||'0'),dir=z(h.subarray(281,4377));
 pos+=4377;const rel=path.posix.join(dir,name).replace(/^\.\//,'');
 if(want.some(w=>rel===w||rel.startsWith(w))){
  const p=path.join(out,rel);fs.mkdirSync(path.dirname(p),{recursive:true});
  const o=fs.openSync(p,'w');const b=Buffer.alloc(1<<20);let r=size,q=pos;
  while(r>0){const n=fs.readSync(fd,b,0,Math.min(r,b.length),q);fs.writeSync(o,b,0,n);r-=n;q+=n}
  fs.closeSync(o);console.log('x',rel,size)}
 pos+=size}
