<?php

require_once(__DIR__."/libutil.php");

function pth2param($opth){
$pth=parse_url($opth);
if(!array_key_exists("query",$pth)||$pth["query"]===null){
return false;
}
parse_str($pth["query"],$param);
return $param;
}

function normalize_path($pth){
$pth=trim($pth);
$pth=parse_url($pth);
if($pth===false){
return false;
}
if(!array_key_exists("path",$pth)||$pth["path"]===null){ $pth["path"]=""; }
$pth="/".$pth["path"]."/";
$pth=preg_replace("#/+#","/",$pth);
$pth=substr($pth,0,-1);
$pth=urldecode($pth);
return $pth;
}