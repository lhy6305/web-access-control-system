<?php

function librsa_getprime($length=1024){
$rn="";
$fl=false;
while($length>=31){
$rn.=str_pad(base_convert(random_int(0,1073741823)|($fl?0:1073741824),10,2),31,"0",STR_PAD_LEFT); //31 bits
$fl=true;
$length-=31;
}
if($length>0){
$rn.=base_convert(random_int(0,(1<<$length)-1)|($fl?0:(1<<($length-1))),10,2);
$fl=true;
}
$rn="0b".$rn;
return gmp_nextprime($rn);
}

function librsa_powmod($a,$b,$n){
return gmp_powm($a,$b,$n);
}

function librsa_get_d($e,$p,$q){
$phi=gmp_mul(gmp_sub($p,1),gmp_sub($q,1));
if((string)gmp_gcd($e,$phi)!=="1"){
return false;
}
return gmp_invert($e,$phi);
}

function librsa_create_rsa($e=65537,$plen=1024){
do{
$p=librsa_getprime($plen);
$q=librsa_getprime($plen);
if((string)$p===(string)$q){
continue;
}
$n=gmp_mul($p,$q);
$d=librsa_get_d($e,$p,$q);
}while($d===false);

// encrypt and decrypt to validate the keys

$r=gmp_random_range(2,gmp_sub($n,1));
$r=gmp_mod($r,$n);
if((string)librsa_powmod(librsa_powmod($r,$e,$n),$d,$n)!==(string)$r){
return false;
}

$r=[];
$r["e"]=(string)$e;
$r["d"]=(string)$d;
$r["n"]=(string)$n;
return $r;
}
