<?php


require_once(__DIR__."/../libhmac_algo.php");

echo libhmac_algo("sha256",64,"The quick brown fox jumped over the lazy dog.","secret")."\r\n";
echo hash_hmac('sha256', 'The quick brown fox jumped over the lazy dog.', 'secret')."\r\n";
