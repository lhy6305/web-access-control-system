<?php
set_time_limit(0);
ob_implicit_flush();
ignore_user_abort(true);
header("Access-Control-Allow-Origin: *");

require_once(__DIR__."/libutil.php");
require_once(__DIR__."/libauthutil.php");
require_once(__DIR__."/libhttpresp.php");


// these params should be set in your nginx settings (or whatever similar to that)


if(!array_key_exists("HTTP_X_REAL_IP",$_SERVER)){
debug_log("HTTP_X_REAL_IP is not set or empty. ".__FILE__."@L".__LINE__);
debug_log(json_encode($_SERVER));
http_gen_500();
exit;
}

if(!array_key_exists("HTTP_X_ORIGINAL_URI",$_SERVER)){
debug_log("HTTP_X_ORIGINAL_URI is not set. ".__FILE__."@L".__LINE__);
debug_log(json_encode($_SERVER));
http_gen_500();
exit;
}

if(!array_key_exists("REQUEST_METHOD",$_SERVER)){
debug_log("REQUEST_METHOD is not set. ".__FILE__."@L".__LINE__);
debug_log(json_encode($_SERVER));
http_gen_500();
exit;
}


$cip=$_SERVER["HTTP_X_REAL_IP"];
$opth=$_SERVER["HTTP_X_ORIGINAL_URI"];
$rmtd=strtolower($_SERVER["REQUEST_METHOD"]);

$pth=normalize_path($opth);
$auth_pth=pth2param($opth);
if($pth===false||$auth_pth===false){
http_gen_400();
exit;
}
unset($auth_pth);
