<?php

function debug_log($str){
$fi=fopen(__DIR__."/debug_log.txt","a");
fwrite($fi,$str."\n");
fclose($fi);
}

function raise_fatal_error($reason){
http_response_code(500);
@header("Content-Type: text/plain;charset=utf-8");
echo $reason;
debug_log($reason);
exit(-1);
}

if(!function_exists("str_starts_with")){
function str_starts_with($haystack,$needle){
if(!is_string($needle)||!is_string($haystack)){
return false;
}
if(strlen($needle)<=0){
return true;
}
if(strlen($needle)>strlen($haystack)){
return false;
}
if(strncmp($needle,$haystack,strlen($needle))==0){
return true;
}
return false;
//end of str_starts_with
}
}

if(!function_exists("mb_str_pad")){
function mb_str_pad($str,$pad_len,$pad_str=" ",$dir=STR_PAD_RIGHT,$encoding=null){
$encoding=($encoding===null?mb_internal_encoding():$encoding);
$padBefore=($dir===STR_PAD_BOTH||$dir===STR_PAD_LEFT);
$padAfter=($dir===STR_PAD_BOTH||$dir===STR_PAD_RIGHT);
$pad_len-=mb_strlen($str,$encoding);
$targetLen=$padBefore&&$padAfter?$pad_len/2:$pad_len;
$strToRepeatLen=mb_strlen($pad_str,$encoding);
$repeatTimes=ceil($targetLen/$strToRepeatLen);
$repeatedString=str_repeat($pad_str,max(0,$repeatTimes)); // safe if used with valid utf-8 strings
$before=$padBefore?mb_substr($repeatedString,0,floor($targetLen),$encoding):"";
$after=$padAfter?mb_substr($repeatedString,0,ceil($targetLen),$encoding):"";
return $before.$str.$after;
//end of mb_str_pad
}
}

function getms(){
list($u,$s)=explode(" ",microtime());
return (string)round(((float)$u+(float)$s)*1000);
}

function getsec(){
return round(getms()/1000);
}

function gen_rand_id($len=32){
return substr(bin2hex(random_bytes(ceil($len/2))),0,$len);
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

function file_put_contents_with_lock($filename, $data) {
$handle=fopen($filename, "c+");
if($handle===false) {
trigger_error("file_put_contents_with_lock: failed to open file ".$filename, E_USER_WARNING);
return false;
}
if(!flock($handle, LOCK_EX)) {
trigger_error("file_put_contents_with_lock: failed to lock file ".$filename." after fopen(...)", E_USER_WARNING);
fclose($handle);
return false;
}
ftruncate($handle, 0);
rewind($handle);
$result=fwrite($handle, $data);
if($result===false) {
trigger_error("file_put_contents_with_lock: failed to write file content of ".$filename, E_USER_WARNING);
} else {
fflush($handle);
}
flock($handle, LOCK_UN);
fclose($handle);
return $result;
}

function file_get_contents_with_lock($filename) {
if(!file_exists($filename) || !is_readable($filename)) {
trigger_error("file_get_contents_with_lock: file ".$filename." not exists or is not readable", E_USER_WARNING);
return false;
}
$handle=fopen($filename, "rb");
if($handle===false) {
trigger_error("file_get_contents_with_lock: failed to open file ".$filename, E_USER_WARNING);
return false;
}
if(!flock($handle, LOCK_SH)) {
trigger_error("file_get_contents_with_lock: failed to lock file ".$filename." after fopen(...)", E_USER_WARNING);
fclose($handle);
return false;
}
$fsize=filesize($filename);
$data=false;
if($fsize===false) {
trigger_error("file_get_contents_with_lock: failed to get size of ".$filename, E_USER_WARNING);
} else if($fsize==0) {
$data="";
} else {
$data=fread($handle, $fsize);
}
flock($handle, LOCK_UN);
fclose($handle);
return $data;
}
