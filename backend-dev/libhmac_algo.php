<?php

require_once(__DIR__."/libutil.php");

function libhmac_algo($hash_func,$block_size,$text,$key){
if(!in_array($hash_func,hash_algos())){
raise_fatal_error("Fatal Error: libhmac_algo(): the \$hash_func (".$hash_func.") provided is not recognized. ".basename(__FILE__)."@L".__LINE__."\r\n");
}
if(strlen($key)>$block_size){
$key=hash($hash_func,$key,true);
}
$key=str_pad($key,$block_size,"\x00",STR_PAD_RIGHT);
$okey=str_repeat("\x5c",$block_size);
for($a=0;$a<$block_size;$a++){
$okey[$a]=chr(ord($okey[$a])^ord($key[$a]));
}
unset($a);
$ikey=str_repeat("\x36",$block_size);
for($a=0;$a<$block_size;$a++){
$ikey[$a]=chr(ord($ikey[$a])^ord($key[$a]));
}
unset($a);
return hash($hash_func,$okey.hash($hash_func,$ikey.$text,true),false);
}
