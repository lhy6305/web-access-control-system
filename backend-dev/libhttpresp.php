<?php

function http_retn_200(){
http_response_code(503); // here 503 means redirect to the real file server in nginx_main.conf
exit;
}

function http_gen_400(){
http_response_code(400);
header("Content-Type: text/html; charset=utf-8");
echo "Error 400/Bad Request";
}

function http_gen_401(){
http_response_code(401);
header("Content-Type: text/html; charset=utf-8");
echo "Error 401/Credential Not Valid";
}

function http_gen_403(){
http_response_code(403);
header("Content-Type: text/html; charset=utf-8");
echo "Error 403/Access Denied";
}

function http_gen_404(){
http_response_code(404);
header("Content-Type: text/html; charset=utf-8");
echo "Error 404/Resource Not Found";
}

function http_gen_405(){
http_response_code(405);
header("Content-Type: text/html; charset=utf-8");
echo "Error 405/Method Not Allowed";
}

function http_gen_500(){
http_response_code(500);
header("Content-Type: text/html; charset=utf-8");
echo "Error 500/Internal Server Error";
}
