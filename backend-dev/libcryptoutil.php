<?php


function str_to_long($str){
$gn=0;
for($i=0;$i<strlen($str);$i++){
$gn=gmp_add(gmp_mul($gn,256),ord($str[$i]));
}
return $gn;
}

function long_to_str($gn){
$str="";
while(gmp_cmp($gn,0)>0){
$byte=gmp_intval(gmp_mod($gn,256));
$str=chr($byte).$str;
$gn=gmp_div_q($gn,256);
}
return $str;
}

function safe_string_compare($a,$b){
$res=true;
if(!(is_string($a)&&is_string($b))){
$res=false;
raise_fatal_error("Fatal Error: safe_string_compare(): at least one param provided is not a string. ".basename(__FILE__)."@L".__LINE__."\r\n");
}
if(strlen($a)!==strlen($b)){
$res=false;
}
$c=max(strlen($a),strlen($b));
$d=strlen($a);
$e=strlen($b);
for($f=0;$f<$c;$f++){
$res=$res&&!(ord($a[$f%$d])^ord($b[$f%$e]));
}
return $res;
}