<?php
require_once 'vendor/autoload.php';
require_once 'WordsAPI/WordsAPI.php';
require_once 'vendor/mashape/unirest-php/src/Unirest.php';

function random_word()
{
    $response = \Unirest\Request::get("https://wordsapiv1.p.mashape.com/words/?random=true",
        array(
            "X-Mashape-Key" => "bm1drDWQbImshgtmxINgRPM8NPkEp12kWEGjsnGfoMOXeglrPe",
            "Accept" => "application/json"
        )
    );

    $words = json_decode($response->raw_body);
    $word = str_replace(' ','', $words->word);
    return $word;
}


?>
