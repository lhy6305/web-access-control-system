<?php


require_once(__DIR__."/../libhmac_algo.php");

$ol=[];

$li=hash_algos();
for($a=0;$a<count($li);$a++){
try{
$t=hash_hmac($li[$a], 'The quick brown fox jumped over the lazy dog.', 'secret');
}catch(ValueError $e){
continue;
}
for($b=1;$b<4096;$b++){
if(libhmac_algo($li[$a],$b,"The quick brown fox jumped over the lazy dog.","secret")===$t){
$ol[$li[$a]]=$b;
break;
}
}
}

$ol=json_encode($ol);

file_put_contents("libhmac_algo_blocksize_list.json",$ol);
