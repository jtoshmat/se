<?php

//**
//save this snippet as url_to_png.php
// usage: php url_to_png.php http://example.com
// Full credit to http://www.os-cms.net/blog/view/5/how-to-create-a-screen-dump-with-php-on-linux
// Modified by Robbie - http://www.category5.tv/
// Distributed by Dr. Abhishek Ghosh under GNU GPL 3.0
// Guessing that no one will come to bite !
// You can delete this comment part for personal usage.
//**

if (!isset($argv[1])){
    die("specify site: e.g. http://example.com\n");
}

$md5 = md5($argv[1]);
$command = "wkhtmltopdf $argv[1] $md5.pdf";
exec($command, $output, $ret);
if ($ret) {
    echo "error fetching screen dump\n";
    die;
}

$command = "convert -density 110 -depth 8 -quality 85 -trim $argv[1].pdf -append $argv[1].png";
exec($command, $output, $ret);
if ($ret){
    echo "Error converting\n";
    die;
}

echo "Conversion compleated: $argv[1] converted to $md5.png\n";
