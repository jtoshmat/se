#!/bin/php
<?php
//Get console argument
var_dump($argv[1]);

$results = [];
for($i=0; $i<1000; $i++){
    $status = urlExists('http://internal.author.local/author/login');
    echo $i.': '.$status. PHP_EOL;

}


function urlExists($url=NULL)
{
    if($url == NULL) return false;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpcode>=200 && $httpcode<=301){
        return 1;
    } else {
        return 0;
    }
}

?>
